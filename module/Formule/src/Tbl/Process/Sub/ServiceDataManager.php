<?php

namespace Formule\Tbl\Process\Sub;

use Application\Entity\Db\Annee;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenTbl\Service\BddServiceAwareTrait;

class ServiceDataManager
{
    use BddServiceAwareTrait;



    public function load(array $params): array
    {
        $data = [];

        $sb = $this->getServiceBdd();
        $conn = $sb->getEntityManager()->getConnection();

        $vIntervenant = $sb->injectKey($sb->getViewDefinition('V_FORMULE_INTERVENANT'), $params);
        $vVolumeHoraire = $sb->injectKey($sb->getViewDefinition('V_FORMULE_VOLUME_HORAIRE'), $params);

        $volumesHoraires = [];
        $query = $conn->executeQuery($vVolumeHoraire);
        while ($vh = $query->fetchAssociative()) {
            for ($etatVolumeHoraire = 1; $etatVolumeHoraire <= (int)$vh['ETAT_VOLUME_HORAIRE_ID']; $etatVolumeHoraire++) {
                $typeVolumeHoraire = (int)$vh['TYPE_VOLUME_HORAIRE_ID'];
                $intervenant = (int)$vh['INTERVENANT_ID'];

                $volumeHoraire = new FormuleVolumeHoraire();
                $this->hydrateVolumeHoraire($vh, $volumeHoraire);

                $volumesHoraires[$intervenant][$typeVolumeHoraire][$etatVolumeHoraire][] = $volumeHoraire;
            }
        }

        $query = $conn->executeQuery($vIntervenant);
        while ($int = $query->fetchAssociative()) {
            foreach ($volumesHoraires as $intervenant => $vhs1) {
                foreach ($vhs1 as $typeVolumeHoraireId => $vhs2) {
                    foreach ($vhs2 as $etatVolumeHoraireId => $vhs) {
                        $typeVolumeHoraire = $sb->getEntityManager()->find(TypeVolumeHoraire::class, $typeVolumeHoraireId);
                        $etatVolumeHoraire = $sb->getEntityManager()->find(EtatVolumeHoraire::class, $etatVolumeHoraireId);

                        $fIntervenant = new FormuleIntervenant();
                        $fIntervenant->setTypeVolumeHoraire($typeVolumeHoraire);
                        $fIntervenant->setEtatVolumeHoraire($etatVolumeHoraire);
                        $this->hydrateIntervenant($int, $fIntervenant);
                        /** @var FormuleVolumeHoraire[] $vhs */
                        foreach ($vhs as $vh) {
                            $fIntervenant->addVolumeHoraire($vh);
                        }

                        $key = $fIntervenant->getId().'-'.$typeVolumeHoraireId.'-'.$etatVolumeHoraireId;
                        $data[$key] = $fIntervenant;
                    }
                }
            }
        }

        return $data;
    }



    protected function hydrateIntervenant(array $data, FormuleIntervenant $intervenant)
    {
        $anneeId = (int)$data['ANNEE_ID'];
        $annee = $this->getServiceBdd()->getEntityManager()->find(Annee::class, $anneeId);

        $intervenant->setId((int)$data['INTERVENANT_ID']);
        $intervenant->setAnnee($annee);

        $intervenant->setStructureCode($data['STRUCTURE_CODE']);
        $intervenant->setHeuresServiceStatutaire((float)$data['HEURES_SERVICE_STATUTAIRE']);
        $intervenant->setHeuresServiceModifie((float)$data['HEURES_SERVICE_MODIFIE']);
        $intervenant->setDepassementServiceDuSansHC($data['DEPASSEMENT_SERVICE_DU_SANS_HC'] === '1');
    }



    protected function hydrateVolumeHoraire(array $data, FormuleVolumeHoraire $volumeHoraire)
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
}