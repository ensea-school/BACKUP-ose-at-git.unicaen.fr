<?php

namespace Application\Service;

use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\ServiceAPayerInterface;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;


/**
 * Description of ServiceAPayer
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceAPayer extends AbstractService
{
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;



    /**
     *
     * @param IntervenantEntity $intervenant
     *
     * @return ServiceAPayerInterface[]
     */
    public function getListByIntervenant(IntervenantEntity $intervenant)
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();

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
        usort($frsList, function ($a, $b) {
            /* @var $a FormuleResultatService */
            /* @var $b FormuleResultatService */
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

            return $aTriStr > $bTriStr;
        });

        usort($frsrList, function ($a, $b) {
            /* @var $a FormuleResultatServiceReferentiel */
            /* @var $b FormuleResultatServiceReferentiel */
            $aTriStr = $a->getStructure()->getLibelleCourt();
            $aTriStr .= ' ' . $a->getServiceReferentiel()->getFonction()->getLibelleCourt();

            $bTriStr = $b->getStructure()->getLibelleCourt();
            $bTriStr .= ' ' . $b->getServiceReferentiel()->getFonction()->getLibelleCourt();

            return $aTriStr > $bTriStr;
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