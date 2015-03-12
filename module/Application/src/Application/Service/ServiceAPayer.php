<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Annee as AnneeEntity;
use Application\Entity\Db\ServiceAPayerInterface;


/**
 * Description of ServiceAPayer
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceAPayer extends AbstractService
{

    /**
     *
     * @param IntervenantEntity $intervenant
     * @param AnneeEntity $annee
     * @return ServiceAPayerInterface[]
     */
    public function getListByIntervenant( IntervenantEntity $intervenant, AnneeEntity $annee )
    {
        $typeVolumeHoraire  = $this->getServiceTypeVolumeHoraire()->getRealise();
        $etatVolumeHoraire  = $this->getServiceEtatVolumeHoraire()->getValide();

        $frsList = $intervenant
                        ->getUniqueFormuleResultat($annee, $typeVolumeHoraire, $etatVolumeHoraire)
                        ->getFormuleResultatService()->filter(
        function( \Application\Entity\Db\FormuleResultatService $formuleResultatService ){
            $totalHC = $formuleResultatService->getHeuresComplFi()
                     + $formuleResultatService->getHeuresComplFa()
                     + $formuleResultatService->getHeuresComplFc()
                     + $formuleResultatService->getHeuresComplFcMajorees();
            return $totalHC > 0;
        });

        $frsrList = $intervenant
                        ->getUniqueFormuleResultat($annee, $typeVolumeHoraire, $etatVolumeHoraire)
                        ->getFormuleResultatServiceReferentiel()->filter(
        function( \Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel ){
            $totalHC = $formuleResultatServiceReferentiel->getHeuresComplReferentiel();
            return $totalHC > 0;
        });

        $result = [];
        foreach( $frsList  as $sap ) $result[] = $sap;
        foreach( $frsrList as $sap ) $result[] = $sap;
        return $result;
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    protected function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\EtatVolumeHoraire
     */
    protected function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
    }

}