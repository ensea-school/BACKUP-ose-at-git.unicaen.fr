<?php

namespace Application\Service\Indicateur\Service\Validation\Referentiel\Prevu;

use Application\Entity\Db\WfEtape;
use Application\Service\Indicateur\Service\Validation\Referentiel\AttenteValidationAbstractIndicateurImpl as BaseAttenteValidationAbstractIndicateurImpl;
use Application\Traits\TypeIntervenantAwareTrait;
use Application\Traits\TypeVolumeHoraireAwareTrait;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AttenteValidationAbstractIndicateurImpl extends BaseAttenteValidationAbstractIndicateurImpl
{
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;
    
    /**
     * Retourne le type de volume horaire utile à cet indicateur.
     * 
     * @return TypeVolumeHoraireEntity
     */
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu();
        }
        
        return $this->typeVolumeHoraire;
    }
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/validation-referentiel', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * 
     * @return string
     */
    protected function getWorkflowStepKey()
    {
        return WfEtape::CODE_REFERENTIEL_VALIDATION;
    }
}