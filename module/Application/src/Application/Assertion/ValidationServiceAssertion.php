<?php

namespace Application\Assertion;

use Common\Exception\LogicException;

/**
 * Assertions concernant la validation d'enseignements.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationServiceAssertion extends ValidationEnsRefAbstractAssertion
{
    protected function assertEntityOld()
    {
        $rule = $this->getServiceLocator()->get('ValidationEnseignementRule')
                ->setIntervenant($this->resource->getIntervenant())
                ->setTypeVolumeHoraire($this->getTypeVolumeHoraire())
                ->setRole($this->role)
                ->execute();
//        var_dump("isAllowed({$this->privilege}) = " . (int)$rule->isAllowed($this->privilege));

        return $rule->isAllowed($this->privilege);
    }
    
    /**
     * @return TypeVolumeHoraire
     */
    protected function getTypeVolumeHoraire()
    {
        $tvh = $this->getMvcEvent()->getParam('typeVolumeHoraire');
        
        if (! $tvh) {
            throw new LogicException("Aucun type de volume horaire spécifié dans l'événement MVC.");
        }
        
        return $tvh;
    }
}