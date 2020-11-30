<?php

namespace Application\Processus\Intervenant;


use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ServiceProcessus
{
    use EntityManagerAwareTrait;
    use IntervenantServiceAwareTrait;


    public function canInitializePrevu(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire)
    {
        $intervenant = $this->getServiceIntervenant()->getPrecedent($intervenant);
        if (!$intervenant) return false;

        $sql = "SELECT valide FROM tbl_service WHERE intervenant_id = :intervenant AND type_volume_horaire_id = :typeVolumeHoraire AND valide > 0 AND ROWNUM = 1";
        $res = $this->getEntityManager()->getConnection()->fetchAll($sql, [
            'intervenant'       => $intervenant->getId(),
            'typeVolumeHoraire' => $typeVolumeHoraire->getId(),
        ]);

        return !empty($res);
    }

}