<?php

namespace Formule\Tbl\Process;


use Application\Entity\Db\Annee;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleServiceIntervenant;
use Formule\Entity\FormuleServiceVolumeHoraire;
use Formule\Model\Arrondisseur\Testeur;
use Formule\Service\FormulatorServiceAwareTrait;
use Formule\Service\FormuleServiceAwareTrait;
use Intervenant\Entity\Db\TypeIntervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Unicaen\BddAdmin\BddAwareTrait;
use Unicaen\BddAdmin\Table;
use UnicaenTbl\Event;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

/**
 * Description of FormuleProcess
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormuleProcess implements ProcessInterface
{
    use FormuleServiceAwareTrait;
    use FormulatorServiceAwareTrait;
    use BddServiceAwareTrait;
    use ContextServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use BddAwareTrait;

    protected Formule $formule;

    /**
     * @var array|FormuleServiceIntervenant[]
     */
    protected array $data = [];

    protected ?Table $resultatIntervenantTable = null;

    protected ?Table $resultatVolumeHoraireTable = null;

    protected Testeur $arrondisseurTesteur;

    protected TableauBord $tableauBord;



    public function __construct()
    {
        $this->arrondisseurTesteur = new Testeur();
    }



    protected function init(array &$params): void
    {
        $this->data = [];

        /* Initialisation de l'année et de la formule à utiliser */
        if (array_key_exists('ANNEE_ID', $params)) {
            $annee = $this->getServiceBdd()->entityGet(Annee::class, $params['ANNEE_ID']);
        } elseif (array_key_exists('INTERVENANT_ID', $params) && !array_key_exists('ANNEE_ID', $params)) {
            $annee = $this->getBdd()->selectOne('SELECT annee_id FROM intervenant WHERE id = :id', ['id' => $params['INTERVENANT_ID']], 'ANNEE_ID');
        } else {
            /* Si l'année n'est pas précisée, alors on ne calcule que sur l'année en cours pour éviter des problèmes de mémoire */
            $annee              = $this->getServiceContext()->getAnnee();
            $params['ANNEE_ID'] = $annee->getId();
        }

        $this->formule = $this->getServiceFormule()->getCurrent($annee);

        if (!$this->resultatIntervenantTable || !$this->resultatVolumeHoraireTable) {
            /* Initialisation des objets tables */
            $bddAdmin = $this->getBdd();

            $this->resultatIntervenantTable   = $bddAdmin->getTable('FORMULE_RESULTAT_INTERVENANT');
            $this->resultatVolumeHoraireTable = $bddAdmin->getTable('FORMULE_RESULTAT_VOLUME_HORAIRE');
        }
    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        $this->tableauBord = $tableauBord;

        if (empty($params)) {
            $annees = $this->getServiceAnnee()->getActives();
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['ANNEE_ID' => $annee->getId()]);
            }
        } else {
            $this->init($params);
            $this->tableauBord->onAction(Event::GET);
            $this->load($params);
            $this->tableauBord->onAction(Event::PROCESS, 0, count($this->data));
            $this->calculer();
            $this->tableauBord->onAction(Event::SET, 0, count($this->data));
            $this->save($params);
        }
    }



    public function getFormuleServiceIntervenant(int $intervenantId, int $typeVolumehoraireId, int $etatVolumeHoraireId, ?int $anneeId): FormuleServiceIntervenant
    {
        $params = [
            'INTERVENANT_ID'         => $intervenantId,
            'TYPE_VOLUME_HORAIRE_ID' => $typeVolumehoraireId,
            'ETAT_VOLUME_HORAIRE_ID' => $etatVolumeHoraireId,
        ];
        if ($anneeId) {
            $params['ANNEE_ID'] = $anneeId;
        }

        $this->init($params);
        $this->load($params);

        unset($params['ANNEE_ID']);
        $intervenantKey = $this->resultatIntervenantTable->makeKey($params, array_keys($params));

        return $this->data[$intervenantKey];
    }



    private function makeSqlIntervenant(Formule $formule, array $params): string
    {
        $sb = $this->getServiceBdd();

        $vIntervenant = $sb->injectKey($sb->getViewDefinition('V_FORMULE_INTERVENANT'), $params);

        $sql = $formule->getSqlIntervenant();
        $sql = str_replace('V_FORMULE_INTERVENANT', '(' . $vIntervenant . ')', $sql);

        return $sql;
    }



    private function makeSqlVolumeHoraire(Formule $formule, array $params): string
    {
        $sb = $this->getServiceBdd();

        $vVolumeHoraire = $sb->injectKey($sb->getViewDefinition('V_FORMULE_VOLUME_HORAIRE'), $params);

        $sql = $formule->getSqlVolumeHoraire() ?? "";
        $sql = str_replace('V_FORMULE_VOLUME_HORAIRE', '(' . $vVolumeHoraire . ')', $sql);

        return $sql;
    }



    protected function hydrateIntervenant(array $data, FormuleServiceIntervenant $intervenant): void
    {
        $sbdd = $this->getServiceBdd();

        $intervenant->setIntervenantId((int)$data['INTERVENANT_ID']);
        $intervenant->setAnnee($sbdd->entityGet(Annee::class, $data['ANNEE_ID']));
        $intervenant->setTypeIntervenant($sbdd->entityGet(TypeIntervenant::class, $data['TYPE_INTERVENANT_ID']));
        $intervenant->setStructureCode($data['STRUCTURE_CODE']);
        $intervenant->setHeuresServiceStatutaire((float)$data['HEURES_SERVICE_STATUTAIRE']);
        $intervenant->setHeuresServiceModifie((float)$data['HEURES_SERVICE_MODIFIE']);
        $intervenant->setDepassementServiceDuSansHC($data['DEPASSEMENT_SERVICE_DU_SANS_HC'] == '1');

        $intervenant->setParam1($data['PARAM_1']);
        $intervenant->setParam2($data['PARAM_2']);
        $intervenant->setParam3($data['PARAM_3']);
        $intervenant->setParam4($data['PARAM_4']);
        $intervenant->setParam5($data['PARAM_5']);

        $intervenant->setArrondisseur(($data['ARRONDISSEUR'] == '1') ? FormuleIntervenant::ARRONDISSEUR_FULL : FormuleIntervenant::ARRONDISSEUR_MINIMAL);
    }



    protected function hydrateVolumeHoraire(array $data, FormuleServiceVolumeHoraire $volumeHoraire): void
    {
        $volumeHoraire->setFormuleResultatIntervenantId((int)$data['FORMULE_RESULTAT_INTERVENANT_ID'] ?: null);
        $volumeHoraire->setVolumeHoraire((int)$data['VOLUME_HORAIRE_ID'] ?: null);
        $volumeHoraire->setVolumeHoraireReferentiel((int)$data['VOLUME_HORAIRE_REF_ID'] ?: null);
        $volumeHoraire->setService((int)$data['SERVICE_ID'] ?: null);
        $volumeHoraire->setServiceReferentiel((int)$data['SERVICE_REFERENTIEL_ID'] ?: null);

        $volumeHoraire->setStructureCode($data['STRUCTURE_CODE']);
        $volumeHoraire->setTypeInterventionCode($data['TYPE_INTERVENTION_CODE']);
        $volumeHoraire->setStructureUniv($data['STRUCTURE_IS_UNIV'] === '1');
        $volumeHoraire->setStructureExterieur($data['STRUCTURE_IS_EXTERIEUR'] === '1');
        $volumeHoraire->setServiceStatutaire($data['SERVICE_STATUTAIRE'] === '1');
        $volumeHoraire->setNonPayable($data['NON_PAYABLE'] === '1');

        $volumeHoraire->setTauxFi((float)$data['TAUX_FI']);
        $volumeHoraire->setTauxFa((float)$data['TAUX_FA']);
        $volumeHoraire->setTauxFc((float)$data['TAUX_FC']);
        $volumeHoraire->setTauxServiceDu((float)$data['TAUX_SERVICE_DU']);
        $volumeHoraire->setTauxServiceCompl((float)$data['TAUX_SERVICE_COMPL']);
        $volumeHoraire->setPonderationServiceDu((float)$data['PONDERATION_SERVICE_DU']);
        $volumeHoraire->setPonderationServiceCompl((float)$data['PONDERATION_SERVICE_COMPL']);

        $volumeHoraire->setParam1($data['PARAM_1']);
        $volumeHoraire->setParam2($data['PARAM_2']);
        $volumeHoraire->setParam3($data['PARAM_3']);
        $volumeHoraire->setParam4($data['PARAM_4']);
        $volumeHoraire->setParam5($data['PARAM_5']);

        $volumeHoraire->setHeures((float)$data['HEURES']);
    }



    private function load(array $params): void
    {
        $sb   = $this->getServiceBdd();
        $conn = $sb->getEntityManager()->getConnection();

        $this->data    = [];
        $fIntervenants = [];

        $vVolumeHoraire = $this->makeSqlVolumeHoraire($this->formule, $params);
        $query          = $conn->executeQuery($vVolumeHoraire);
        while ($vhData = $query->fetchAssociative()) {
            $intervenantId       = (int)$vhData['INTERVENANT_ID'];
            $typeVolumeHoraireId = (int)$vhData['TYPE_VOLUME_HORAIRE_ID'];
            $etatVolumeHoraireId = (int)$vhData['ETAT_VOLUME_HORAIRE_ID'];

            $intervenantKey = $this->resultatIntervenantTable->makeKey($vhData, ['INTERVENANT_ID', 'TYPE_VOLUME_HORAIRE_ID', 'ETAT_VOLUME_HORAIRE_ID']);

            if (!array_key_exists($intervenantKey, $this->data)) {
                $fIntervenant = new FormuleServiceIntervenant();
                $fIntervenant->setId((int)$vhData['FORMULE_RESULTAT_INTERVENANT_ID'] ?: null);
                $fIntervenant->setTypeVolumeHoraire($sb->entityGet(TypeVolumeHoraire::class, $typeVolumeHoraireId));
                $fIntervenant->setEtatVolumeHoraire($sb->entityGet(EtatVolumeHoraire::class, $etatVolumeHoraireId));
                $this->data[$intervenantKey] = $fIntervenant;
                if (!array_key_exists($intervenantId, $fIntervenants)) {
                    $fIntervenants[$intervenantId] = [];
                }
                $fIntervenants[$intervenantId][] = $fIntervenant;
            } else {
                $fIntervenant = $this->data[$intervenantKey];
            }

            $volumeHoraire = new FormuleServiceVolumeHoraire();
            $this->hydrateVolumeHoraire($vhData, $volumeHoraire);
            $fIntervenant->addVolumeHoraire($volumeHoraire);

        }

        $vIntervenant = $this->makeSqlIntervenant($this->formule, $params);
        $query        = $conn->executeQuery($vIntervenant);
        while ($iData = $query->fetchAssociative()) {
            $intervenantId = (int)$iData['INTERVENANT_ID'];
            if (array_key_exists($intervenantId, $fIntervenants)) {
                foreach ($fIntervenants[$intervenantId] as $fIntervenant) {
                    $this->hydrateIntervenant($iData, $fIntervenant);
                }
            }
        }

        unset($fIntervenants);
    }



    protected function calculer(): void
    {
        $formulator = $this->getServiceFormulator();
        $index      = 0;
        $count      = count($this->data);
        foreach ($this->data as $formuleIntervenant) {
            $index++;
            $this->tableauBord->onAction(Event::PROGRESS, $index, $count);
            $formulator->calculer($formuleIntervenant, $this->formule, true);
            //$trace = $formuleIntervenant->getArrondisseurTrace();
        }
    }



    protected function extractIntervenant(FormuleServiceIntervenant $intervenant): array
    {
        return [
            'INTERVENANT_ID'                 => $intervenant->getIntervenantId(),
            'ANNEE_ID'                       => $intervenant->getAnnee()->getId(),
            'TYPE_VOLUME_HORAIRE_ID'         => $intervenant->getTypeVolumeHoraire()->getId(),
            'ETAT_VOLUME_HORAIRE_ID'         => $intervenant->getEtatVolumeHoraire()->getId(),
            'TYPE_INTERVENANT_ID'            => $intervenant->getTypeIntervenant()->getId(),
            'STRUCTURE_CODE'                 => $intervenant->getStructureCode(),
            'HEURES_SERVICE_STATUTAIRE'      => $intervenant->getHeuresServiceStatutaire(),
            'HEURES_SERVICE_MODIFIE'         => $intervenant->getHeuresServiceModifie(),
            'DEPASSEMENT_SERVICE_DU_SANS_HC' => $intervenant->isDepassementServiceDuSansHC(),
            'PARAM_1'                        => $intervenant->getParam1(),
            'PARAM_2'                        => $intervenant->getParam2(),
            'PARAM_3'                        => $intervenant->getParam3(),
            'PARAM_4'                        => $intervenant->getParam4(),
            'PARAM_5'                        => $intervenant->getParam5(),
            'SERVICE_DU'                     => $intervenant->getServiceDu(),
            'HEURES_SERVICE_FI'              => 0,
            'HEURES_SERVICE_FA'              => 0,
            'HEURES_SERVICE_FC'              => 0,
            'HEURES_SERVICE_REFERENTIEL'     => 0,
            'HEURES_NON_PAYABLE_FI'          => 0,
            'HEURES_NON_PAYABLE_FA'          => 0,
            'HEURES_NON_PAYABLE_FC'          => 0,
            'HEURES_NON_PAYABLE_REFERENTIEL' => 0,
            'HEURES_COMPL_FI'                => 0,
            'HEURES_COMPL_FA'                => 0,
            'HEURES_COMPL_FC'                => 0,
            'HEURES_COMPL_REFERENTIEL'       => 0,
            'HEURES_PRIMES'                  => 0,
            'TOTAL'                          => 0,
            'SOLDE'                          => 0,
            'SOUS_SERVICE'                   => 0,
        ];
    }



    protected function extractVolumeHoraire(FormuleServiceVolumeHoraire $volumeHoraire): array
    {
        return [
            'FORMULE_RESULTAT_INTERVENANT_ID' => $volumeHoraire->getFormuleResultatIntervenantId(),
            'VOLUME_HORAIRE_ID'               => $volumeHoraire->getVolumeHoraire(),
            'VOLUME_HORAIRE_REF_ID'           => $volumeHoraire->getVolumeHoraireReferentiel(),
            'SERVICE_ID'                      => $volumeHoraire->getService(),
            'SERVICE_REFERENTIEL_ID'          => $volumeHoraire->getServiceReferentiel(),
            'STRUCTURE_CODE'                  => $volumeHoraire->getStructureCode(),
            'TYPE_INTERVENTION_CODE'          => $volumeHoraire->getTypeInterventionCode(),
            'STRUCTURE_UNIV'                  => $volumeHoraire->isStructureUniv(),
            'SERVICE_STATUTAIRE'              => $volumeHoraire->isServiceStatutaire(),
            'NON_PAYABLE'                     => $volumeHoraire->isNonPayable(),
            'TAUX_FI'                         => $volumeHoraire->getTauxFi(),
            'TAUX_FA'                         => $volumeHoraire->getTauxFa(),
            'TAUX_FC'                         => $volumeHoraire->getTauxFc(),
            'TAUX_SERVICE_DU'                 => $volumeHoraire->getTauxServiceDu(),
            'TAUX_SERVICE_COMPL'              => $volumeHoraire->getTauxServiceCompl(),
            'PONDERATION_SERVICE_DU'          => $volumeHoraire->getPonderationServiceDu(),
            'PONDERATION_SERVICE_COMPL'       => $volumeHoraire->getPonderationServiceCompl(),
            'HEURES'                          => $volumeHoraire->getHeures(),
            'PARAM_1'                         => $volumeHoraire->getParam1(),
            'PARAM_2'                         => $volumeHoraire->getParam2(),
            'PARAM_3'                         => $volumeHoraire->getParam3(),
            'PARAM_4'                         => $volumeHoraire->getParam4(),
            'PARAM_5'                         => $volumeHoraire->getParam5(),
            'HEURES_SERVICE_FI'               => $volumeHoraire->getHeuresServiceFi(),
            'HEURES_SERVICE_FA'               => $volumeHoraire->getHeuresServiceFa(),
            'HEURES_SERVICE_FC'               => $volumeHoraire->getHeuresServiceFc(),
            'HEURES_SERVICE_REFERENTIEL'      => $volumeHoraire->getHeuresServiceReferentiel(),
            'HEURES_NON_PAYABLE_FI'           => $volumeHoraire->getHeuresNonPayableFi(),
            'HEURES_NON_PAYABLE_FA'           => $volumeHoraire->getHeuresNonPayableFa(),
            'HEURES_NON_PAYABLE_FC'           => $volumeHoraire->getHeuresNonPayableFc(),
            'HEURES_NON_PAYABLE_REFERENTIEL'  => $volumeHoraire->getHeuresNonPayableReferentiel(),
            'HEURES_COMPL_FI'                 => $volumeHoraire->getHeuresComplFi(),
            'HEURES_COMPL_FA'                 => $volumeHoraire->getHeuresComplFa(),
            'HEURES_COMPL_FC'                 => $volumeHoraire->getHeuresComplFc(),
            'HEURES_COMPL_REFERENTIEL'        => $volumeHoraire->getHeuresComplReferentiel(),
            'HEURES_PRIMES'                   => $volumeHoraire->getHeuresPrimes(),
            'TOTAL'                           => $volumeHoraire->getTotal(),
        ];
    }



    protected function save(array $params): void
    {
        $totalCols = [
            'HEURES_SERVICE_FI',
            'HEURES_SERVICE_FA',
            'HEURES_SERVICE_FC',
            'HEURES_SERVICE_REFERENTIEL',
            'HEURES_NON_PAYABLE_FI',
            'HEURES_NON_PAYABLE_FA',
            'HEURES_NON_PAYABLE_FC',
            'HEURES_NON_PAYABLE_REFERENTIEL',
            'HEURES_COMPL_FI',
            'HEURES_COMPL_FA',
            'HEURES_COMPL_FC',
            'HEURES_COMPL_REFERENTIEL',
            'HEURES_PRIMES',
            'TOTAL',
        ];

        $rIntervenants    = [];
        $rVolumesHoraires = [];
        $keyColumns       = ['INTERVENANT_ID', 'TYPE_VOLUME_HORAIRE_ID', 'ETAT_VOLUME_HORAIRE_ID'];

        foreach ($this->data as $fIntervenant) {
            $rIntervenant    = $this->extractIntervenant($fIntervenant);
            $volumesHoraires = $fIntervenant->getVolumesHoraires();
            foreach ($volumesHoraires as $volumesHoraire) {
                $rVolumesHoraire = $this->extractVolumeHoraire($volumesHoraire);
                /* Si l'ID n'est pas encore déterminé, on crée une valeur clé qui permettra de le retrouver ensuite */
                if (empty($rVolumesHoraire['FORMULE_RESULTAT_INTERVENANT_ID'])) {
                    $rVolumesHoraire['z_FORMULE_RESULTAT_INTERVENANT_ID'] = $this->resultatIntervenantTable->makeKey($rIntervenant, $keyColumns);
                }
                /* On fait les totaux ici */
                foreach ($totalCols as $col) {
                    $rIntervenant[$col] += $rVolumesHoraire[$col];
                }
                $rVolumesHoraires[] = $rVolumesHoraire;
            }
            foreach ($totalCols as $col) { // arrondi à cause de PHP
                $rIntervenant[$col] = round($rIntervenant[$col], 2);
            }

            /* Petits calculs de solde et de sous-service ici */
            $rIntervenant['SOLDE'] = $rIntervenant['TOTAL'] - $rIntervenant['SERVICE_DU'];
            if ($rIntervenant['SOLDE'] >= 0.0) {
                $rIntervenant['SOUS_SERVICE'] = 0.0;
            } else {
                $rIntervenant['SOUS_SERVICE'] = $rIntervenant['SOLDE'] * -1;
            }

            $rIntervenants[] = $rIntervenant;
        }
        $this->data = []; // libération de mémoire

        $fIntervenantSelect = "
        SELECT
          t.*, i.statut_id
        FROM
          formule_resultat_intervenant t
          JOIN intervenant i ON i.id = t.intervenant_id
        ";
        $options            = [
            'custom-select'      => $fIntervenantSelect,
            'where'              => $params,
            'return-insert-data' => true,
            'transaction'        => !isset($params['INTERVENANT_ID']),
            'callback'           => function (string $action, int $progress, int $total, array $data = [], array $key = []) {
                $this->tableauBord->onAction(Event::PROGRESS, $progress, $total);
            },
        ];
        $res                = $this->resultatIntervenantTable->merge($rIntervenants, $keyColumns, $options);
        // Fin du travail au niveau des données intervenants
        unset($rIntervenants);

        // Si des ID de résultats d'intervenants ont été ajoutés, on les injecte dans les volumes horaires avant leur insertion en BDD sur la base des clés fournies plus tôt
        $insertedIntervenants = $res['insert-data'];
        if (!empty($insertedIntervenants)) {
            foreach ($rVolumesHoraires as $rvhi => $rVolumesHoraire) {
                if (isset($rVolumesHoraire['z_FORMULE_RESULTAT_INTERVENANT_ID'])) {
                    $key = $rVolumesHoraire['z_FORMULE_RESULTAT_INTERVENANT_ID'];
                    if (empty($rVolumesHoraire['FORMULE_RESULTAT_INTERVENANT_ID']) && isset($insertedIntervenants[$key])) {
                        $rVolumesHoraires[$rvhi]['FORMULE_RESULTAT_INTERVENANT_ID'] = $insertedIntervenants[$key]['ID'];
                    }
                }
            }
            // Données plus nécessaires : on libère la mémoire
            unset($insertedIntervenants);
        }


        /* Requête personnalisée nécessaire pour remonter les données à confronter aux paramètres, nécessaire au filtrage */
        $fVolumeHoraireSelect = "
        SELECT
          t.*, i.statut_id, fri.intervenant_id, fri.type_intervenant_id, fri.type_volume_horaire_id, fri.etat_volume_horaire_id, i.annee_id
        FROM
          formule_resultat_volume_horaire t
          JOIN formule_resultat_intervenant fri ON fri.id = t.FORMULE_RESULTAT_INTERVENANT_ID
          JOIN intervenant i ON i.id = fri.intervenant_id
        ";
        $options              = [
            'custom-select'      => $fVolumeHoraireSelect,
            'where'              => $params,
            'return-insert-data' => false,
            'transaction'        => !isset($params['INTERVENANT_ID']),
            'callback'           => function (string $action, int $progress, int $total, array $data = [], array $key = []) {
                $this->tableauBord->onAction(Event::PROGRESS, $progress, $total);
            },
        ];
        $keyColumns           = ['FORMULE_RESULTAT_INTERVENANT_ID', 'VOLUME_HORAIRE_ID', 'VOLUME_HORAIRE_REF_ID'];
        $this->resultatVolumeHoraireTable->merge($rVolumesHoraires, $keyColumns, $options);
    }
}