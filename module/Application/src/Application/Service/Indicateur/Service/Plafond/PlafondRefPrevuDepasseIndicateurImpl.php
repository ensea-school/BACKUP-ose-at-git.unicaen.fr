<?php

namespace Application\Service\Indicateur\Service\Plafond;


/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PlafondRefPrevuDepasseIndicateurImpl extends PlafondRefDepasseAbstractIndicateurImpl
{
    public function getTypeVolumeHoraire()
    {
        if (!parent::getTypeVolumeHoraire()) {
            $sTvh = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
            /* @var $sTvh \Application\Service\TypeVolumeHoraire */
            $this->setTypeVolumeHoraire($sTvh->getPrevu());
        }

        return parent::getTypeVolumeHoraire();
    }
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultItemUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/services', 
                ['intervenant' => $result->getIntervenant()->getRouteParam()], 
                ['force_canonical' => true]);
    }
}