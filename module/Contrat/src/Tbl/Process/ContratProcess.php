<?php

namespace Contrat\Tbl\Process;


use Administration\Entity\Db\Parametre;
use Administration\Service\ParametresServiceAwareTrait;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Paiement\Service\TauxRemuServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

class ContratProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use BddAwareTrait;
    use ParametresServiceAwareTrait;
    use TauxRemuServiceAwareTrait;
    use AnneeServiceAwareTrait;

    private string $codeEns = 'ENS';

    private string $codeMis = 'MIS';

    private array $tauxRemuUuid       = [];
    private array $intervenantContrat = [];

    protected array $services = [];

    protected array $tblData = [];

    //Regle sur les avenants des parametres generaux
    private string $regleA;
    private string $regleEns;
    private string $regleMis;
    private string $regleTermine;



    public function __construct()
    {
        /* new process */
    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {

        if (empty($params)) {
            $annees = $this->getServiceAnnee()->getActives();
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['ANNEE_ID' => $annee->getId()]);
            }
        } else {
            $this->init($params);
            $this->loadAContractualiser($params);
            $this->traitement($params);
            $this->exporter();
            $this->enregistrement($tableauBord, $params);
            $this->clear();
        }
    }



    public function debug(array $params = []): array
    {
        $this->init();
        $this->heuresAContractualiserSql($params);
        $this->traitement($params);

        return $this->services;
    }



    public function init(array $params = []): void
    {
        $parametres = $this->getServiceParametres();


        $this->regleA       = $parametres->get('avenant');
        $this->regleEns     = $parametres->get('contrat_ens');
        $this->regleMis     = $parametres->get('contrat_mis');
        $this->regleTermine = $parametres->get('contrat_regle_franchissement');

        $this->services = [];
        $this->tblData  = [];
    }



    protected function loadAContractualiser(array $params): void
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAContractualiserSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params)
            . ' ORDER BY intervenant_id, contrat_id ASC';

        $servicesContrat = $this->getBdd()->selectEach($sql);

        $taux_remu_temp = 0;
        $listeContrat   = [];
        while ($serviceContrat = $servicesContrat->next()) {
            $res            = $this->traitementQuery($serviceContrat, $listeContrat, $taux_remu_temp);
            $listeContrat   = $res[0];
            $taux_remu_temp = $res[1];
        }

        // on vide pour limiter la conso de RAM
        unset($servicesContrat);
        unset($listeContrat);
    }



    protected function heuresAContractualiserSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT');
    }



    public function traitement(array $params): void
    {


        foreach ($this->services as $id => $service) {
            $uuid = $service['UUID'];
            // Calcul du taux a afficher dans le contrat selon les services se retrouvant dans un même contrat
            $service['TAUX_REMU_VALEUR']        = NULL;
            $service['TAUX_REMU_DATE']          = NULL;
            $service['TAUX_REMU_MAJORE_VALEUR'] = NULL;
            $service['TAUX_REMU_MAJORE_DATE']   = NULL;


            if ($service['CONTRAT_ID'] != NULL) {

                if (($this->regleTermine == Parametre::CONTRAT_FRANCHI_VALIDATION && $service['EDITE'] == 1)
                    || ($this->regleTermine == Parametre::CONTRAT_FRANCHI_DATE_RETOUR && $service['SIGNE'] == 1)
                ) {
                    $service['TERMINE'] = 1;
                } else {
                    $service['TERMINE'] = 0;
                }
            } else {
                $service['TERMINE'] = 0;

                if (($service['TYPE_SERVICE_CODE'] != 'MIS' && $this->regleEns == Parametre::CONTRAT_ENS_GLOBALE)
                    ||
                    ($service['TYPE_SERVICE_CODE'] == 'MIS' && $this->regleMis == Parametre::CONTRAT_MIS_GLOBALE)) {
                    $service['STRUCTURE_ID'] = NULL;
                }
            }

            if ($this->tauxRemuUuid[$uuid]) {
                //Calcul de la valeur et date du taux
                $tauxRemuId       = $service['TAUX_REMU_ID'];
                $tauxRemuMajoreId = isset($service['TAUX_REMU_MAJORE_ID']) ? $service['TAUX_REMU_MAJORE_ID'] : null;
                if ($service['CONTRAT_ID'] != NULL) {
                    $date                        = $service['DATE_DEBUT'] ?? $service['DATE_CREATION'];
                    $tauxRemuValeur              = $this->getServiceTauxRemu()->tauxValeur($tauxRemuId, $date);
                    $service['TAUX_REMU_VALEUR'] = $tauxRemuValeur;
                    $service['TAUX_REMU_DATE']   = $date;

                    if ($tauxRemuMajoreId != NULL) {
                        $tauxRemuMajoreValeur               = $this->getServiceTauxRemu()->tauxValeur($tauxRemuMajoreId, $date);
                        $service['TAUX_REMU_MAJORE_VALEUR'] = $tauxRemuMajoreValeur;
                        $service['TAUX_REMU_MAJORE_DATE']   = $date;
                    }
                }
            }


            //Calcul pour savoir si le contrat devra être un avenant ou un contrat
            if ($service['TYPE_CONTRAT_ID'] == NULL) {
                if ($service['TYPE_SERVICE_CODE'] != 'MIS') {

                    if (isset($this->intervenantContrat[$service['INTERVENANT_ID']])) {
                        $service['TYPE_CONTRAT_ID']   = 2;
                        $service['CONTRAT_PARENT_ID'] = $this->intervenantContrat[$service['INTERVENANT_ID']];
                    }


                }
                if ($service['TYPE_SERVICE_CODE'] == 'MIS') {
                    if ($this->regleMis == Parametre::CONTRAT_MIS_COMPOSANTE) {
                        if (isset($this->intervenantContrat[$service['STRUCTURE_ID']])) {
                            $service['TYPE_CONTRAT_ID']   = 2;
                            $service['CONTRAT_PARENT_ID'] = $this->intervenantContrat[$service['STRUCTURE_ID']];

                        }
                    }
                    if ($this->regleMis == Parametre::CONTRAT_MIS_MISSION) {
                        if (isset($this->intervenantContrat[$service['MISSION_ID']])) {
                            $service['TYPE_CONTRAT_ID']   = 2;
                            $service['CONTRAT_PARENT_ID'] = $this->intervenantContrat[$service['MISSION_ID']];

                        }

                    }
                    if ($this->regleMis == Parametre::CONTRAT_MIS_GLOBALE) {
                        if (isset($this->intervenantContrat[$service['INTERVENANT_ID']])) {
                            $service['TYPE_CONTRAT_ID']   = 2;
                            $service['CONTRAT_PARENT_ID'] = $this->intervenantContrat[$service['INTERVENANT_ID']];
                        }
                    }
                }

                if ($service['TYPE_CONTRAT_ID'] == NULL) {
                    $service['TYPE_CONTRAT_ID'] = 1;
                }
                if ($service['TYPE_CONTRAT_ID'] == 2 && $this->regleA == Parametre::AVENANT_DESACTIVE) {
                    $service['ACTIF'] = 0;
                } else {
                    $service['ACTIF'] = 1;
                }
            }

            $this->services[$id] = $service;
        }

        $serviceBdd     = $this->getServiceBdd();
        $sqlVTblContrat = $serviceBdd->getViewDefinition('V_TBL_CONTRAT');

        $sqlSansHeure = "SELECT 
                    c.id contrat_id, 
                    c.contrat_id contrat_parent_id,
                    c.histo_creation date_creation,
                    c.debut_validite date_debut,
                    c.fin_validite date_fin,
                    c.structure_id,
                    vtblcp.mission_id,
                    c.numero_avenant,
                    c.process_signature_id,
                    c.validation_id,
                    vtblcp.taux_remu_id ,
                    vtblcp.taux_remu_majore_id,
                    vtblcp.autre_libelle,
                    vtblcp.taux_conges_payes,
                    vtblcp.type_service_id,
                    i.id intervenant_id,
                    i.annee_id,
                    c.process_signature_id process_id
                FROM contrat c
                JOIN INTERVENANT i ON c.intervenant_id = i.id
                LEFT JOIN ($sqlVTblContrat) vtbl ON c.id = vtbl.contrat_id
                LEFT JOIN ($sqlVTblContrat) vtblcp ON c.contrat_id = vtblcp.contrat_id
                WHERE vtbl.contrat_id IS NULL
                  AND c.histo_destruction IS NULL
                  /*@INTERVENANT_ID=c.intervenant_id*/
                  /*@ANNEE_ID=i.annee_id*/
                  ";


        $sqlSansHeure      = $serviceBdd->injectKey($sqlSansHeure, $params);
        $contratsSansHeure = $this->getBdd()->selectEach($sqlSansHeure);


        while ($contratSansHeure = $contratsSansHeure->next()) {
            $contratToTbl                      = [];
            $contratToTbl['INTERVENANT_ID']    = $contratSansHeure['INTERVENANT_ID'];
            $contratToTbl['ANNEE_ID']          = $contratSansHeure['ANNEE_ID'];
            $contratToTbl['STRUCTURE_ID']      = $contratSansHeure['STRUCTURE_ID'];
            $contratToTbl['CONTRAT_PARENT_ID'] = $contratSansHeure['CONTRAT_PARENT_ID'];

            $contratToTbl['UUID'] = 'avenant_' . $contratSansHeure['INTERVENANT_ID'] . '_' . $contratSansHeure['CONTRAT_PARENT_ID'] . '_' . $contratSansHeure['CONTRAT_ID'];

            $contratToTbl['EDITE']                  = 0;
            $contratToTbl['SIGNE']                  = 0;
            $contratToTbl['TERMINE']                = 0;
            $contratToTbl['AUTRES']                 = 0;
            $contratToTbl['AUTRE_LIBELLE']          = $contratSansHeure['AUTRE_LIBELLE'];
            $contratToTbl['CM']                     = 0;
            $contratToTbl['TD']                     = 0;
            $contratToTbl['TP']                     = 0;
            $contratToTbl['CONTRAT_ID']             = $contratSansHeure['CONTRAT_ID'];
            $contratToTbl['TYPE_CONTRAT_ID']        = 2;
            $contratToTbl['DATE_CREATION']          = $contratSansHeure['DATE_CREATION'];
            $contratToTbl['DATE_DEBUT']             = $contratSansHeure['DATE_DEBUT'];
            $contratToTbl['DATE_FIN']               = $contratSansHeure['DATE_FIN'];
            $contratToTbl['HETD']                   = 0;
            $contratToTbl['HEURES']                 = 0;
            $contratToTbl['MISSION_ID']             = $contratSansHeure['MISSION_ID'];
            $contratToTbl['SERVICE_ID']             = NULL;
            $contratToTbl['SERVICE_REFERENTIEL_ID'] = NULL;
            $contratToTbl['TAUX_CONGES_PAYES']      = $contratSansHeure['TAUX_CONGES_PAYES'];


            $contratToTbl['TAUX_REMU_MAJORE_ID'] = $contratSansHeure['TAUX_REMU_MAJORE_ID'];
            $contratToTbl['TAUX_REMU_ID']        = $contratSansHeure['TAUX_REMU_ID'];
            $contratToTbl['TAUX_REMU_DATE']      = NULL;
            $contratToTbl['TAUX_REMU_VALEUR']    = NULL;

            $contratToTbl['TAUX_REMU_MAJORE_DATE']   = NULL;
            $contratToTbl['TAUX_REMU_MAJORE_VALEUR'] = NULL;

            if ($contratSansHeure['TAUX_REMU_ID'] != NULL) {
                $date           = $contratSansHeure['DATE_DEBUT'] ?? $contratSansHeure['DATE_CREATION'];
                $tauxRemuValeur = $this->getServiceTauxRemu()->tauxValeur($contratSansHeure['TAUX_REMU_ID'], $date);

                $contratToTbl['TAUX_REMU_DATE']   = $date;
                $contratToTbl['TAUX_REMU_VALEUR'] = $tauxRemuValeur;

                if ($contratSansHeure['TAUX_REMU_MAJORE_ID'] != NULL) {
                    $tauxRemuValeurMajore                    = $this->getServiceTauxRemu()->tauxValeur($contratSansHeure['TAUX_REMU_MAJORE_ID'], $date);
                    $contratToTbl['TAUX_REMU_MAJORE_DATE']   = $date;
                    $contratToTbl['TAUX_REMU_MAJORE_VALEUR'] = $tauxRemuValeurMajore;

                }


            }

            $contratToTbl['VOLUME_HORAIRE_ID']         = NULL;
            $contratToTbl['VOLUME_HORAIRE_REF_ID']     = NULL;
            $contratToTbl['VOLUME_HORAIRE_MISSION_ID'] = NULL;
            $contratToTbl['TYPE_SERVICE_ID']           = $contratSansHeure['TYPE_SERVICE_ID'];
            $contratToTbl['PROCESS_ID']                = $contratSansHeure['PROCESS_ID'];

            $contratToTbl['ACTIF'] = 1;


            $this->services[$contratToTbl['UUID']] = $contratToTbl;
        }


        $sql = "WITH contrat_et_avenants AS (
    SELECT 
        c.id AS contrat_id_principal,
        c.debut_validite AS date_debut,
        c.fin_validite AS date_fin_contrat,
        CASE pm.valeur
            WHEN 'contrat_mis_mission'       
            THEN c.structure_id 
            ELSE NULL
            END AS parent_structure_id,
        vtblc.mission_id AS mission_id_principal,
        ap.fin_validite AS date_fin_avenant,
        i.id intervenant_id,
        i.annee_id
    FROM 
        contrat c
    JOIN INTERVENANT i ON c.intervenant_id = i.id
    JOIN parametre pm ON pm.nom = 'contrat_mis'
    JOIN type_contrat tc ON tc.code = 'CONTRAT'
    LEFT JOIN 
        contrat ap ON c.id = ap.contrat_id AND (ap.histo_destruction IS NULL)
    LEFT JOIN
        ($sqlVTblContrat) vtblc ON vtblc.contrat_id = c.id
    WHERE 
        c.histo_destruction IS NULL
        AND c.type_contrat_id = tc.id        
        /*@INTERVENANT_ID=c.intervenant_id*/
        /*@ANNEE_ID=i.annee_id*/
),
contrats_max_dates AS (
    SELECT 
        contrat_id_principal,
        date_debut,
        parent_structure_id,
        mission_id_principal,
        intervenant_id,
        annee_id,
        MAX(GREATEST(date_fin_avenant, date_fin_contrat)) AS max_date_fin_contrat
    FROM 
        contrat_et_avenants
    GROUP BY 
        contrat_id_principal, date_debut, parent_structure_id, mission_id_principal, intervenant_id, annee_id
),
missions_dates AS (
    SELECT 
        vtblc.mission_id,
        MAX(m.date_fin) AS max_date_fin_mission
    FROM 
        ($sqlVTblContrat) vtblc
    JOIN 
        mission m ON vtblc.mission_id = m.id
    WHERE 
        m.histo_destruction IS NULL
    GROUP BY 
        vtblc.mission_id
)
SELECT 
    cma.contrat_id_principal AS contrat_parent_id,
    cma.date_debut AS date_debut,
    cma.parent_structure_id AS structure_id,
    cma.intervenant_id,
    vtblc.mission_id             mission_id,
    cma.max_date_fin_contrat     date_fin_contrat,
    md.max_date_fin_mission      max_date_fin_mission,
    vtblc.type_service_id,
    cma.annee_id
FROM 
    contrats_max_dates cma
LEFT JOIN 
    missions_dates md ON cma.mission_id_principal = md.mission_id
LEFT JOIN 
    ($sqlVTblContrat) vtblc ON vtblc.contrat_id = cma.contrat_id_principal
WHERE 
    cma.max_date_fin_contrat < md.max_date_fin_mission";

        $sql = $serviceBdd->injectKey($sql, $params);

        $avenantNecessaireDate = $this->getBdd()->select($sql);

        foreach ($avenantNecessaireDate as $avenant) {

            $newAvenant                      = [];
            $newAvenant['INTERVENANT_ID']    = $avenant['INTERVENANT_ID'] ? (int)$avenant['INTERVENANT_ID'] : NULL;
            $newAvenant['ANNEE_ID']          = $avenant['ANNEE_ID'] ? (int)$avenant['ANNEE_ID'] : NULL;
            $newAvenant['STRUCTURE_ID']      = $avenant['STRUCTURE_ID'] ? (int)$avenant['STRUCTURE_ID'] : NULL;
            $newAvenant['CONTRAT_PARENT_ID'] = $avenant['CONTRAT_PARENT_ID'] ? (int)$avenant['CONTRAT_PARENT_ID'] : NULL;

            $newAvenant['UUID'] = 'avenant_' . $newAvenant['INTERVENANT_ID'] . '_' . $newAvenant['CONTRAT_PARENT_ID'];

            $newAvenant['EDITE']           = 0;
            $newAvenant['SIGNE']           = 0;
            $newAvenant['TERMINE']         = 0;
            $newAvenant['TYPE_CONTRAT_ID'] = 2;
            $newAvenant['MISSION_ID']      = $avenant['MISSION_ID'] ?  (int)$avenant['MISSION_ID'] :NULL;
            $newAvenant['TYPE_SERVICE_ID'] = $avenant['TYPE_SERVICE_ID'] ? (int) $avenant['TYPE_SERVICE_ID'] : NULL;
            $newAvenant['AUTRES']                    = 0;
            $newAvenant['AUTRE_LIBELLE']             = NULL;
            $newAvenant['CM']                        = 0;
            $newAvenant['TD']                        = 0;
            $newAvenant['TP']                        = 0;
            $newAvenant['CONTRAT_ID']                = NULL;
            $newAvenant['DATE_CREATION']             = NULL;
            $newAvenant['DATE_DEBUT']                = $avenant['DATE_DEBUT'];
            $newAvenant['DATE_FIN']                  = $avenant['MAX_DATE_FIN_MISSION'];
            $newAvenant['HETD']                      = 0;
            $newAvenant['HEURES']                    = 0;
            $newAvenant['SERVICE_ID']                = NULL;
            $newAvenant['SERVICE_REFERENTIEL_ID']    = NULL;
            $newAvenant['TAUX_CONGES_PAYES']         = NULL;
            $newAvenant['TAUX_REMU_DATE']            = NULL;
            $newAvenant['TAUX_REMU_ID']              = NULL;
            $newAvenant['TAUX_REMU_MAJORE_DATE']     = NULL;
            $newAvenant['TAUX_REMU_MAJORE_ID']       = NULL;
            $newAvenant['TAUX_REMU_MAJORE_VALEUR']   = NULL;
            $newAvenant['TAUX_REMU_VALEUR']          = NULL;
            $newAvenant['VOLUME_HORAIRE_ID']         = NULL;
            $newAvenant['VOLUME_HORAIRE_REF_ID']     = NULL;
            $newAvenant['VOLUME_HORAIRE_MISSION_ID'] = NULL;
            $newAvenant['PROCESS_ID']                = NULL;

            if ($this->regleA == Parametre::AVENANT_AUTORISE) {
                $newAvenant['ACTIF'] = 1;
            } else {
                $newAvenant['ACTIF'] = 0;

            }

            $this->services[$newAvenant['UUID']] = $newAvenant;

        }


    }



    private function exporter(): void
    {
        foreach ($this->services as $service) {

            $ldata = [
                'INTERVENANT_ID'            => $service['INTERVENANT_ID'],
                'ANNEE_ID'                  => $service['ANNEE_ID'],
                'STRUCTURE_ID'              => $service['STRUCTURE_ID'],
                'EDITE'                     => $service['EDITE'],
                'SIGNE'                     => $service['SIGNE'],
                'TERMINE'                   => $service['TERMINE'],
                'ACTIF'                     => $service['ACTIF'],
                'AUTRES'                    => $service['AUTRES'],
                'AUTRE_LIBELLE'             => $service['AUTRE_LIBELLE'],
                'CM'                        => $service['CM'],
                'TD'                        => $service['TD'],
                'TP'                        => $service['TP'],
                'CONTRAT_ID'                => $service['CONTRAT_ID'],
                'CONTRAT_PARENT_ID'         => $service['CONTRAT_PARENT_ID'],
                'TYPE_CONTRAT_ID'           => $service['TYPE_CONTRAT_ID'],
                'DATE_CREATION'             => $service['DATE_CREATION'],
                'DATE_DEBUT'                => $service['DATE_DEBUT'],
                'DATE_FIN'                  => $service['DATE_FIN'],
                'HETD'                      => $service['HETD'],
                'HEURES'                    => $service['HEURES'],
                'MISSION_ID'                => $service['MISSION_ID'],
                'SERVICE_ID'                => $service['SERVICE_ID'],
                'SERVICE_REFERENTIEL_ID'    => $service['SERVICE_REFERENTIEL_ID'],
                'TAUX_CONGES_PAYES'         => $service['TAUX_CONGES_PAYES'],
                'TAUX_REMU_DATE'            => $service['TAUX_REMU_DATE'],
                'TAUX_REMU_ID'              => $service['TAUX_REMU_ID'],
                'TAUX_REMU_MAJORE_DATE'     => $service['TAUX_REMU_MAJORE_DATE'],
                'TAUX_REMU_MAJORE_ID'       => $service['TAUX_REMU_MAJORE_ID'],
                'TAUX_REMU_MAJORE_VALEUR'   => $service['TAUX_REMU_MAJORE_VALEUR'],
                'TAUX_REMU_VALEUR'          => $service['TAUX_REMU_VALEUR'],
                'VOLUME_HORAIRE_ID'         => $service['VOLUME_HORAIRE_ID'],
                'VOLUME_HORAIRE_REF_ID'     => $service['VOLUME_HORAIRE_REF_ID'],
                'VOLUME_HORAIRE_MISSION_ID' => $service['VOLUME_HORAIRE_MISSION_ID'],
                'UUID'                      => $service['UUID'],
                'TYPE_SERVICE_ID'           => $service['TYPE_SERVICE_ID'],
                'PROCESS_ID'                => $service['PROCESS_ID'],
            ];

            $this->tblData[] = $ldata;
        }


    }



    protected function enregistrement(TableauBord $tableauBord, array $params): void
    {
        // Enregistrement en BDD
        $key = $tableauBord->getOption('key');

        $table = $this->getBdd()->getTable('TBL_CONTRAT');

//         on force la DDL pour éviter de faire des requêtes en plus
//        $table->setDdl(['sequence' => $tableauBord->getOption('sequence'), 'columns' => array_fill_keys($tableauBord->getOption('cols'), [])]);

        $options = [
            'where'              => $params,
            'return-insert-data' => false,
            'transaction'        => !isset($params['INTERVENANT_ID']),
        ];

        $table->merge($this->tblData, $key, $options);
        // on vide pour limiter la conso de RAM
        $this->tblData = [];
    }



    public function getServices(): array
    {
        return $this->services;
    }



    private function clear(): void
    {
        unset($this->services);
        unset($this->tblData);
        unset($this->tauxRemuUuid);
    }



    /**
     * @param array $serviceContrat
     * @param array $listeContrat
     * @param mixed $taux_remu_temp
     * @return array
     */
    public function traitementQuery(array $serviceContrat, array $listeContrat, mixed $taux_remu_temp): array
    {
        $uuid        = $serviceContrat['UUID'];
        $taux_remu   = $serviceContrat['TAUX_REMU_ID'];
        $typeContrat = $serviceContrat['TYPE_CONTRAT_ID'];
        if ($typeContrat != null && $serviceContrat['TYPE_SERVICE_CODE'] != 'MIS') {
            $listeContrat[$serviceContrat['INTERVENANT_ID']][0] = true;
        } else if ($typeContrat != null && $serviceContrat['TYPE_SERVICE_CODE'] == 'MIS') {
            $listeContrat[$serviceContrat['INTERVENANT_ID']][$serviceContrat['MISSION_ID']] = true;
        }
        if ($serviceContrat['CONTRAT_ID'] != null) {
            if ($serviceContrat['TYPE_SERVICE_CODE'] != 'MIS') {
                $this->intervenantContrat[$serviceContrat['INTERVENANT_ID']] = $serviceContrat['CONTRAT_ID'];
            }
            if ($serviceContrat['TYPE_SERVICE_CODE'] == 'MIS') {
                if ($this->regleMis == Parametre::CONTRAT_MIS_COMPOSANTE) {
                    $this->intervenantContrat[$serviceContrat['STRUCTURE_ID']] = $serviceContrat['CONTRAT_ID'];
                }
                if ($this->regleMis == Parametre::CONTRAT_MIS_MISSION) {
                    $this->intervenantContrat[$serviceContrat['MISSION_ID']] = $serviceContrat['CONTRAT_ID'];
                }
                if ($this->regleMis == Parametre::CONTRAT_MIS_GLOBALE) {
                    $this->intervenantContrat[$serviceContrat['INTERVENANT_ID']] = $serviceContrat['CONTRAT_ID'];
                }

            }
        }

        $this->services[] = $serviceContrat;
        if (!isset($this->tauxRemuUuid[$uuid])) {
            $this->tauxRemuUuid[$uuid] = true;
            $taux_remu_temp            = null;
        }
        if ($taux_remu_temp == null) {
            $taux_remu_temp = $taux_remu;
        } elseif ($taux_remu_temp != $taux_remu && $this->tauxRemuUuid[$uuid]) {
            $this->tauxRemuUuid[$uuid] = false;
        }


        return [$listeContrat, $taux_remu_temp];
    }



    public function getTauxRemuUuid(): array
    {
        return $this->tauxRemuUuid;
    }



    public function getIntervenantContrat(): array
    {
        return $this->intervenantContrat;
    }



    public function clearAfterTest()
    {
        // on vide pour limiter la conso de RAM
        $this->regleA             = '';
        $this->intervenantContrat = [];
        $this->tauxRemuUuid       = [];
        $this->services           = [];
        $this->tblData            = [];
    }
}