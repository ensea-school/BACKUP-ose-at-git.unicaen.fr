<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of DossierValideRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierValideRule extends IntervenantRule
{
    use \Application\Traits\TypeValidationAwareTrait;
    
    public function execute()
    {
        $validationsDossier = $this->getIntervenant()->getValidation($this->getTypeValidation());
        if (!count($validationsDossier)) {
            return false;
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur && null !== $this->getIntervenant()->getDossier();
    }
}
