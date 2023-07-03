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
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();

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

        $frsList = $intervenant
            ->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire)
            ->getFormuleResultatService()->filter(
                function (\Application\Entity\Db\FormuleResultatService $formuleResultatService) {
                    $totalHC = $formuleResultatService->getHeuresComplFi()
                        + $formuleResultatService->getHeuresComplFa()
                        + $formuleResultatService->getHeuresComplFc()
                        + $formuleResultatService->getHeuresComplFcMajorees();

                    return $totalHC > 0 || $formuleResultatService->getMiseEnPaiement()->count() > 0;
                })
            ->toArray();

        $frsrList = $intervenant
            ->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire)
            ->getFormuleResultatServiceReferentiel()->filter(
                function (\Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel) {
                    $totalHC = $formuleResultatServiceReferentiel->getHeuresComplReferentiel();

                    return $totalHC > 0 || $formuleResultatServiceReferentiel->getMiseEnPaiement()->count() > 0;
                })
            ->toArray();

        /* Tri */
        @usort($frsList, function ($a, $b) {
            /* @var $a \Application\Entity\Db\FormuleResultatService */
            /* @var $b \Application\Entity\Db\FormuleResultatService */
            $aTriStr = $a->getStructure()->getLibelleCourt();
            if ($element = $a->getService()->getElementPedagogique()) {
                $aTriStr .= ' ' . $element->getEtape()->getLibelle();
                $aTriStr .= ' ' . $element->getSourceCode();
            } else {
                $aTriStr .= ' zzzzzzz ' . $a->getService()->getEtablissement()->getLibelle();
            }

            $bTriStr = $b->getStructure()->getLibelleCourt();
            if ($element = $b->getService()->getElementPedagogique()) {
                $bTriStr .= ' ' . $element->getEtape()->getLibelle();
                $bTriStr .= ' ' . $element->getSourceCode();
            } else {
                $bTriStr .= ' zzzzzzz ' . $b->getService()->getEtablissement()->getLibelle();
            }

            return $aTriStr > $bTriStr ? 1 : 0;
        });

        usort($frsrList, function ($a, $b) {
            /* @var $a \Application\Entity\Db\FormuleResultatServiceReferentiel */
            /* @var $b \Application\Entity\Db\FormuleResultatServiceReferentiel */
            $aTriStr = $a->getStructure()->getLibelleCourt();
            $aTriStr .= ' ' . $a->getServiceReferentiel()->getFonctionReferentiel()->getLibelleCourt();

            $bTriStr = $b->getStructure()->getLibelleCourt();
            $bTriStr .= ' ' . $b->getServiceReferentiel()->getFonctionReferentiel()->getLibelleCourt();

            return $aTriStr > $bTriStr ? 1 : 0;
        });

        $result = [];
        foreach ($frsList as $sap) {
            $result[] = $sap;
        }
        foreach ($frsrList as $sap) {
            $result[] = $sap;
        }

        return $result;
    }
}