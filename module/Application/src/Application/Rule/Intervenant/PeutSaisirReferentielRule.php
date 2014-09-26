<?php

namespace Application\Rule\Intervenant;

use Application\Traits\StructureAwareTrait;

/**
 * Règle métier déterminant si du référentiel peut être saisi pour un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirReferentielRule extends IntervenantRule
{
    use StructureAwareTrait;
    
    public function execute()
    {
        $estPermanent = new EstPermanentRule($this->getIntervenant());
        if (!$estPermanent->execute()) {
            $this->setMessage($estPermanent->getMessage());
            return false;
        }
        
        if ($this->getStructure()) {
            $estAffecte = new EstAffecteRule($this->getIntervenant(), $this->getStructure());
            if (!$estAffecte->execute()) {
                $this->setMessage($estAffecte->getMessage());
                return false;
            }
        }
        
        return true;
    }
    
    public function isRelevant()
    {
        return true;
    }
}