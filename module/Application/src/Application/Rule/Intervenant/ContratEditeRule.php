<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of ContratEditeRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratEditeRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    
    public function execute()
    {
        $validationsContrat = $this->getIntervenant()->getValidation($this->getTypeValidation());
        if (!count($validationsContrat)) {
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
    }
}
