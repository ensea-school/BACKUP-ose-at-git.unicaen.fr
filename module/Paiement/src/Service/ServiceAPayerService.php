<?php

namespace Paiement\Service;

use Application\Entity\Db\Intervenant;
use Application\Service\AbstractService;
use Paiement\Entity\Db\ServiceAPayerInterface;
use Paiement\Entity\Db\TblPaiement;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;


/**
 * Description of ServiceAPayer
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceAPayerService extends AbstractService
{
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;


    /**
     *
     * @param Intervenant $intervenant
     *
     * @return ServiceAPayerInterface[]
     */
    public function getListByIntervenant(Intervenant $intervenant)
    {
        $dql = "
        SELECT
            tp
        FROM
            ".TblPaiement::class." tp
        WHERE
            tp. intervenant = :intervenant
        ";
        /** @var TblPaiement[] $meps */
        $meps = $this->getEntityManager()->createQuery($dql)->setParameters(['intervenant' => $intervenant])->getResult();

        $saps = [];
        foreach( $meps as $mep ){
            if ($mep->getHeuresAPayer() > 0 || $mep->getMiseEnPaiement()) {
                $sap = $mep->getServiceAPayer();
                $sapId = get_class($sap) . '@' . $sap->getId();
                $saps[$sapId] = $sap;
            }
        }
        return $saps;
    }
}