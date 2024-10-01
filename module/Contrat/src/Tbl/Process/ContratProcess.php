<?php

namespace Contrat\Tbl\Process;


use Application\Entity\Db\Parametre;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Paiement\Service\TauxRemuServiceAwareTrait;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

class ContratProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
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



    public function __construct()
    {
        /* new process */
    }



    public function init(array $params = [])
    {
        $parametres = $this->getServiceParametres();


        $this->regleA   = $parametres->get('avenant');
        $this->regleEns = $parametres->get('contrat_ens');
        $this->regleMis = $parametres->get('contrat_mis');

        $this->services = [];
        $this->tblData  = [];
    }



    public function run(TableauBord $tableauBord, array $params = [])
    {

        if (empty($params)) {
            $annees = $this->getServiceAnnee()->getActives();
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['ANNEE_ID' => $annee->getId()]);
            }
        } else {
            $this->init($params);
            $this->loadAContractualiser($params);
            $this->traitement();
            $this->exporter();
            $this->enregistrement($tableauBord, $params);
            $this->clear();
        }
    }



    public function debug(array $params = []): array
    {
        $this->init();
        $this->heuresAContractualiserSql($params);
        $this->traitement();

        return $this->services;
    }



    public function traitement()
    {
        foreach ($this->services as $id => $service) {
            $uuid = $service['UUID'];

            // Calcul du taux a afficher dans le contrat selon les services se retrouvant dans un même contrat
            $service['TAUX_REMU_VALEUR']        = null;
            $service['TAUX_REMU_DATE']          = null;
            $service['TAUX_REMU_MAJORE_VALEUR'] = null;
            $service['TAUX_REMU_MAJORE_DATE']   = null;
            if ($this->tauxRemuUuid[$uuid]) {
                //Calcul de la valeur et date du taux
                $tauxRemuId       = $service['TAUX_REMU_ID'];
                $tauxRemuMajoreId = isset($service['TAUX_REMU_MAJORE_ID']) ? $service['TAUX_REMU_MAJORE_ID'] : null;
                if ($service['CONTRAT_ID'] != null) {
                    $date                        = $service['DATE_DEBUT'] > $service['DATE_CREATION'] ? $service['DATE_DEBUT'] : $service['DATE_CREATION'];
                    $tauxRemuValeur              = $this->getServiceTauxRemu()->tauxValeur($tauxRemuId, $date);
                    $service['TAUX_REMU_VALEUR'] = $tauxRemuValeur;
                    $service['TAUX_REMU_DATE']   = $date;

                    if ($tauxRemuMajoreId != null) {
                        $tauxRemuMajoreValeur               = $this->getServiceTauxRemu()->tauxValeur($tauxRemuMajoreId, $date);
                        $service['TAUX_REMU_MAJORE_VALEUR'] = $tauxRemuMajoreValeur;
                        $service['TAUX_REMU_MAJORE_DATE']   = $date;
                    }
                }
            }

            //Calcul pour savoir si le contrat devra être un avenant ou un contrat
            if ($service["TYPE_CONTRAT_ID"] == null) {

                $contratPresent = false;
                if ($service['TYPE_SERVICE_CODE'] != 'MIS') {
                    if ($this->regleMis == Parametre::CONTRAT_ENS_COMPOSANTE) {
                        if (isset($this->intervenantContrat[$service['STRUCTURE_ID']])) {
                            $service["TYPE_CONTRAT_ID"] = 2;
                            $service["CONTRAT_ID"]      = $this->intervenantContrat[$service['STRUCTURE_ID']];
                        }
                    }
                    if ($this->regleMis == Parametre::CONTRAT_ENS_GLOBALE) {
                        if (isset($this->intervenantContrat[$service['INTERVENANT_ID']])) {
                            $service["TYPE_CONTRAT_ID"] = 2;
                            $service["CONTRAT_ID"]      = $this->intervenantContrat[$service['INTERVENANT_ID']];

                        }
                    }
                }
                if ($service['TYPE_SERVICE_CODE'] == 'MIS') {
                    if ($this->regleMis == Parametre::CONTRAT_MIS_COMPOSANTE) {
                        if (isset($this->intervenantContrat[$service['STRUCTURE_ID']])) {
                            $service["TYPE_CONTRAT_ID"] = 2;
                            $service["CONTRAT_ID"]      = $this->intervenantContrat[$service['STRUCTURE_ID']];

                        }
                    }
                    if ($this->regleMis == Parametre::CONTRAT_MIS_MISSION) {
                        if (isset($this->intervenantContrat[$service['MISSION_ID']])) {
                            $service["TYPE_CONTRAT_ID"] = 2;
                            $service["CONTRAT_ID"]      = $this->intervenantContrat[$service['MISSION_ID']];

                        }

                    }
                    if ($this->regleMis == Parametre::CONTRAT_MIS_GLOBALE) {
                        if (isset($this->intervenantContrat[$service['INTERVENANT_ID']])) {
                            $service["TYPE_CONTRAT_ID"] = 2;
                            $service["CONTRAT_ID"]      = $this->intervenantContrat[$service['INTERVENANT_ID']];

                        }
                    }

                }

                if ($service["TYPE_CONTRAT_ID"] == null) {
                    $service["TYPE_CONTRAT_ID"] = 1;
                }
                if ($service["TYPE_CONTRAT_ID"] == 2 && $this->regleA == Parametre::AVENANT_DESACTIVE) {
                    $service['ACTIF'] = 0;
                }
            }

            $this->services[$id] = $service;
        }
    }



    public function getServices(): array
    {
        return $this->services;
    }



    protected function enregistrement(TableauBord $tableauBord, array $params)
    {
        // Enregistrement en BDD
        $key = $tableauBord->getOption('key');

        $table = \OseAdmin::instance()->getBdd()->getTable('TBL_CONTRAT');

        // on force la DDL pour éviter de faire des requêtes en plus
        $table->setDdl(['sequence' => $tableauBord->getOption('sequence'), 'columns' => array_fill_keys($tableauBord->getOption('cols'), [])]);
        // on merge dans la table

        $options = [
            'where'              => $params,
            'return-insert-data' => false,
        ];

        $table->merge($this->tblData, $key, $options);
        // on vide pour limiter la conso de RAM
        $this->tblData = [];
    }



    protected function loadAContractualiser(array $params)
    {
        $conn = $this->getServiceBdd()->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM ('
            . $this->getServiceBdd()->injectKey($this->heuresAContractualiserSql(), $params)
            . ') t '
            . $this->getServiceBdd()->makeWhere($params)
            . ' ORDER BY intervenant_id, contrat_id ASC';

        $servicesContrat = $conn->executeQuery($sql);
        $taux_remu_temp  = 0;
        $listeContrat    = [];
        while ($serviceContrat = $servicesContrat->fetchAssociative()) {
            $res            = $this->traitementQuery($serviceContrat, $listeContrat, $taux_remu_temp);
            $listeContrat   = $res[0];
            $taux_remu_temp = $res[1];
        }
        unset($servicesContrat);
        unset($listeContrat);
    }



    protected function heuresAContractualiserSql(): string
    {
        return $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT');
    }



    private function exporter()
    {
        foreach ($this->services as $service) {

            $ldata           = [
                "INTERVENANT_ID"            => $service["INTERVENANT_ID"],
                "ANNEE_ID"                  => $service["ANNEE_ID"],
                "STRUCTURE_ID"              => $service["STRUCTURE_ID"],
                "EDITE"                     => $service["EDITE"],
                "SIGNE"                     => $service["SIGNE"],
                "ACTIF"                     => $service["ACTIF"],
                "AUTRES"                    => $service["AUTRES"],
                "AUTRE_LIBELLE"             => $service["AUTRE_LIBELLE"],
                "CM"                        => $service["CM"],
                "TD"                        => $service["TD"],
                "TP"                        => $service["TP"],
                "CONTRAT_ID"                => $service["CONTRAT_ID"],
                "CONTRAT_PARENT_ID"         => $service["CONTRAT_PARENT_ID"],
                "TYPE_CONTRAT_ID"           => $service["TYPE_CONTRAT_ID"],
                "DATE_CREATION"             => $service["DATE_CREATION"],
                "DATE_DEBUT"                => $service["DATE_DEBUT"],
                "DATE_FIN"                  => $service["DATE_FIN"],
                "HETD"                      => $service["HETD"],
                "HEURES"                    => $service["HEURES"],
                "MISSION_ID"                => $service["MISSION_ID"],
                "SERVICE_ID"                => $service["SERVICE_ID"],
                "SERVICE_REFERENTIEL_ID"    => $service["SERVICE_REFERENTIEL_ID"],
                "TAUX_CONGES_PAYES"         => $service["TAUX_CONGES_PAYES"],
                "TAUX_REMU_DATE"            => $service["TAUX_REMU_DATE"],
                "TAUX_REMU_ID"              => $service["TAUX_REMU_ID"],
                "TAUX_REMU_MAJORE_DATE"     => $service["TAUX_REMU_MAJORE_DATE"],
                "TAUX_REMU_MAJORE_ID"       => $service["TAUX_REMU_MAJORE_ID"],
                "TAUX_REMU_MAJORE_VALEUR"   => $service["TAUX_REMU_MAJORE_VALEUR"],
                "TAUX_REMU_VALEUR"          => $service["TAUX_REMU_VALEUR"],
                "VOLUME_HORAIRE_ID"         => $service["VOLUME_HORAIRE_ID"],
                "VOLUME_HORAIRE_REF_ID"     => $service["VOLUME_HORAIRE_REF_ID"],
                "VOLUME_HORAIRE_MISSION_ID" => $service["VOLUME_HORAIRE_MISSION_ID"],
                "UUID"                      => $service["UUID"],
                "TYPE_SERVICE_ID"           => $service["TYPE_SERVICE_ID"],
                "PROCESS_ID"                => $service["PROCESS_ID"],
                //A retirer apres refonte calcul workflow
                "NBVH"                      => 1,
            ];
            $this->tblData[] = $ldata;
        }


    }



    private function clear()
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
            $listeContrat[$serviceContrat["INTERVENANT_ID"]][0] = true;
        } else if ($typeContrat != null && $serviceContrat['TYPE_SERVICE_CODE'] == 'MIS') {
            $listeContrat[$serviceContrat["INTERVENANT_ID"]][$serviceContrat["MISSION_ID"]] = true;
        }

        if ($serviceContrat['CONTRAT_ID'] != null) {
            if ($serviceContrat['TYPE_SERVICE_CODE'] != 'MIS') {
                if ($this->regleMis == Parametre::CONTRAT_ENS_COMPOSANTE) {
                    $this->intervenantContrat[$serviceContrat['STRUCTURE_ID']] = $serviceContrat['CONTRAT_ID'];
                }
                if ($this->regleMis == Parametre::CONTRAT_ENS_GLOBALE) {
                    $this->intervenantContrat[$serviceContrat['INTERVENANT_ID']] = $serviceContrat['CONTRAT_ID'];
                }

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
        $this->regleA             = '';
        $this->intervenantContrat = [];
        $this->tauxRemuUuid       = [];
        $this->services           = [];
        $this->tblData            = [];
    }
}