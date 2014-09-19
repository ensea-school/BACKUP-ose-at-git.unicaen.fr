<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\IntervenantExterieur;
use Application\Traits\StructureAwareTrait;
use Application\Traits\TypeContratAwareTrait;

/**
 * Description of PossedeContratRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeContratRule extends IntervenantRule
{
    use TypeContratAwareTrait;
    use StructureAwareTrait;
    
    public function execute()
    {
        $contrats = $this->getIntervenant()->getContrat($this->getTypeContrat(), $this->getStructure());
        
        // filtrage éventuel selon la présence d'une validation ou non
        if (null !== ($estValide = $this->getValide())) {
            $f = function(Contrat $c) use ($estValide) { 
                return $estValide && $c->getValidation() || !$estValide && !$c->getValidation();
            };
            $contrats = $contrats->filter($f);
        }
        
        if (count($contrats)) {
            return true;
        }
        
        return false;
    }
    
    public function isRelevant()
    {
        return $this->getIntervenant() instanceof IntervenantExterieur;
    }
    
    protected $valide = null;
    
    public function getValide()
    {
        return $this->valide;
    }

    public function setValide($valide = true)
    {
        $this->valide = $valide;
        return $this;
    }
}
