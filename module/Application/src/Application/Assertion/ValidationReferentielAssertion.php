<?php

namespace Application\Assertion;

use Application\Rule\Validation\ValidationReferentielRule;

/**
 * Assertions concernant la validation de référentiel.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationReferentielAssertion extends ValidationEnsRefAbstractAssertion
{
    /**
     * @return boolean
     */
    protected function assertEntityOld()
    {
        $rule = $this->getRuleValidationReferentiel();
        $rule->execute();
//        var_dump("isAllowed({$this->privilege}) = " . (int)$rule->isAllowed($this->privilege));

        return $rule->isAllowed($this->privilege);
    }
    
    /**
     * @return ValidationReferentielRule
     */
    private function getRuleValidationReferentiel()
    {
        return $this->getServiceLocator()->get('ValidationReferentielRule')
                ->setIntervenant($this->resource->getIntervenant())
                ->setTypeVolumeHoraire($this->getTypeVolumeHoraire())
                ->setRole($this->role);
    }
    
    /**
     * @return TypeVolumeHoraire
     */
    protected function getTypeVolumeHoraire()
    {
        if (!count($this->resource->getVolumeHoraireReferentiel())) {
            throw new LogicException(
                    "Impossible de déterminer le type de volume horaire car la validation ne possède aucun volume horaire.");
        }
        
        return $this->resource->getVolumeHoraireReferentiel()[0]->getTypeVolumeHoraire();
    }
}