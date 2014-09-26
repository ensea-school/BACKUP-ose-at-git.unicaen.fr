<?php

namespace Application\Rule\Intervenant;

use Application\Traits\StructureAwareTrait;

/**
 * Règle métier déterminant si un intervenant peut faire l'objet d'une modification de service dû.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirModificationServiceDuRule extends IntervenantRule
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