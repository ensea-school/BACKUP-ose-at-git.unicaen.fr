<?php

namespace Formule\Tbl\Process;


use Application\Entity\Db\Annee;
use Application\Service\Traits\ContextServiceAwareTrait;
use Formule\Entity\Db\Formule;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Formule\Service\FormulatorServiceAwareTrait;
use Formule\Service\FormuleServiceAwareTrait;
use Formule\Tbl\Process\Sub\ServiceDataManager;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
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

    protected Annee $annee;

    protected array $entityCache = [];

    /**
     * @var array|FormuleIntervenant[]
     */
    protected array $data = [];

    protected array $resultatsIntervenants = [];

    protected array $resultatsVolumesHoraires = [];



    public function __construct()
    {

    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        if (array_key_exists('ANNEE_ID', $params)) {
            $this->annee = $this->getServiceBdd()->entityGet(Annee::class, $params['ANNEE_ID']);
        } else {
            /* Si l'année n'est pas précisée, alors on ne calcule que sur l'année en cours pour éviter des problèmes de mémoire */
            $this->annee = $this->getServiceContext()->getAnnee();
            $params['ANNEE_ID'] = $this->annee->getId();
        }

        $this->load($params);
        $this->calculer();
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

        $sql = $formule->getSqlVolumeHoraire();
        $sql = str_replace('V_FORMULE_VOLUME_HORAIRE', '(' . $vVolumeHoraire . ')', $sql);

        return $sql;
    }



    protected function hydrateIntervenant(array $data, FormuleIntervenant $intervenant): void
    {
        $intervenant->setId((int)$data['INTERVENANT_ID']);
        $intervenant->setAnnee($this->annee);

        $intervenant->setStructureCode($data['STRUCTURE_CODE']);
        $intervenant->setHeuresServiceStatutaire((float)$data['HEURES_SERVICE_STATUTAIRE']);
        $intervenant->setHeuresServiceModifie((float)$data['HEURES_SERVICE_MODIFIE']);
        $intervenant->setDepassementServiceDuSansHC($data['DEPASSEMENT_SERVICE_DU_SANS_HC'] === '1');
    }



    protected function hydrateVolumeHoraire(array $data, FormuleVolumeHoraire $volumeHoraire): void
    {
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
        $volumeHoraire->setHeures((float)$data['HEURES']);
    }



    private function load(array $params): void
    {
        $sb = $this->getServiceBdd();
        $conn = $sb->getEntityManager()->getConnection();

        $formule = $this->getServiceFormule()->getCurrent($this->annee);

        $this->data = [];
        $fIntervenants = [];

        $vVolumeHoraire = $this->makeSqlVolumeHoraire($formule, $params);
        $query = $conn->executeQuery($vVolumeHoraire);
        while ($vhData = $query->fetchAssociative()) {
            $intervenantId = (int)$vhData['INTERVENANT_ID'];
            $typeVolumeHoraireId = (int)$vhData['TYPE_VOLUME_HORAIRE_ID'];
            $etatVolumeHoraireIdMax = (int)$vhData['ETAT_VOLUME_HORAIRE_ID'];

            for ($etatVolumeHoraireId = 1; $etatVolumeHoraireId <= $etatVolumeHoraireIdMax; $etatVolumeHoraireId++) {
                $intervenantKey = $intervenantId . '-' . $typeVolumeHoraireId . '-' . $etatVolumeHoraireId;

                if (!array_key_exists($intervenantKey, $this->data)) {
                    $fIntervenant = new FormuleIntervenant();
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

                $volumeHoraire = new FormuleVolumeHoraire();
                $this->hydrateVolumeHoraire($vhData, $volumeHoraire);
                $fIntervenant->addVolumeHoraire($volumeHoraire);
            }
        }

        $vIntervenant = $this->makeSqlIntervenant($formule, $params);
        $query = $conn->executeQuery($vIntervenant);
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
        $formule = $this->getServiceFormule()->getCurrent($this->annee);
        foreach ($this->data as $key => $formuleIntervenant) {
            $this->getServiceFormulator()->calculer($formuleIntervenant, $formule);

        }
    }
}