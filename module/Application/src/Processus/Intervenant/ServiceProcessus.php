<?php

namespace Application\Processus\Intervenant;


use Application\Entity\Db\Intervenant;
use Service\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ServiceProcessus
{
    use EntityManagerAwareTrait;
    use IntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;


    public function initializeRealise(Intervenant $intervenant): ?TypeVolumeHoraire
    {
        $constatationServiceTvh = $this->getServiceParametres()->get('constatation_realise');
        $typeVolumeHoraire      = $this->getServiceTypeVolumeHoraire()->getByCode($constatationServiceTvh);

        if (!$typeVolumeHoraire) return null;

        $sql = "SELECT valide FROM tbl_service WHERE intervenant_id = :intervenant AND type_volume_horaire_id = :typeVolumeHoraire AND valide > 0";
        $res = $this->getEntityManager()->getConnection()->fetchOne($sql, [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
        ]);

        if ($res) {
            return $typeVolumeHoraire;
        } else {
            return null;
        }
    }



    public function initializePrevu(Intervenant $intervenant): ?TypeVolumeHoraire
    {
        $reportServiceTvh  = $this->getServiceParametres()->get('report_service');
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($reportServiceTvh);

        if (!$typeVolumeHoraire) return null;

        $intervenant = $this->getServiceIntervenant()->getPrecedent($intervenant);
        if (!$intervenant) return null;

        $sql = "SELECT valide FROM tbl_service WHERE intervenant_id = :intervenant AND type_volume_horaire_id = :typeVolumeHoraire AND valide > 0";
        $res = $this->getEntityManager()->getConnection()->fetchOne($sql, [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
        ]);

        if ($res) {
            return $typeVolumeHoraire;
        } else {
            return null;
        }
    }

}