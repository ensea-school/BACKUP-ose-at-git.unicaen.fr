<?php

namespace Application\Processus\Intervenant;


use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TblService;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ServiceProcessus
{
    use EntityManagerAwareTrait;
    use IntervenantServiceAwareTrait;



    public function canPrevuToPrevu(Intervenant $intervenant)
    {
        $intervenant = $this->getServiceIntervenant()->getPrecedent($intervenant);
        if (!$intervenant) return false;

        $dql = "
        SELECT
          s        
        FROM
          " . TblService::class . " s
          JOIN s.typeVolumeHoraire tvh
        WHERE
            s.intervenant = :intervenant
            AND tvh.code = 'PREVU'
            AND s.valide > 0
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setMaxResults(1);
        $query->setParameters(compact('intervenant'));
        $s = $query->getResult();

        return count($s) > 0;

    }

}