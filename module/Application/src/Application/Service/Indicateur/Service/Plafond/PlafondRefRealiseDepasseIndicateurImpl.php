<?php

namespace Application\Service\Indicateur\Service\Plafond;

use Application\Service\Indicateur\Service\Plafond\PlafondRefDepasseAbstractIndicateurImpl;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PlafondRefRealiseDepasseIndicateurImpl extends PlafondRefDepasseAbstractIndicateurImpl
{
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getRealise();
        }
        
        return $this->typeVolumeHoraire;
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
                'intervenant/services-realises', 
                ['intervenant' => $result->getIntervenant()->getSourceCode()], 
                ['force_canonical' => true]);
    }
}