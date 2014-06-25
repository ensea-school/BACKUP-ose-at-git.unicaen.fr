<?php

namespace Application\Rule\Intervenant;

/**
 * Description of ServiceValideRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceValideRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    use \Application\Traits\StructureAwareTrait;
    
    public function execute()
    {
        $validations = $this->getIntervenant()->getValidation($this->getTypeValidation());
        if (!count($validations)) {
            return false;
        }
//        
//        if (null !== $this->getStructure()) {
//            $f = function(Validation $validation) use ($this) { return $this->getStructure() === $validation->getStructure(); };
//        }
//        
//        if (!count($validations)) {
//            return false;
//        }
        
        
        return true;
    }
    
    public function isRelevant()
    {
        return !$this->getIntervenant()->getStatut()->estAutre();
    }
}
