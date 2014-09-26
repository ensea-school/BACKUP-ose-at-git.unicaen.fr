<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;

/**
 * Règle métier déterminant si un intervenant est affecté à une structure de niveau 2 précise ou à l'une de ses sous-structures.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EstAffecteRule extends IntervenantRule
{
    private $structure;
    
    public function __construct(Intervenant $intervenant, Structure $structure)
    {
        parent::__construct($intervenant);
        $this->structure = $structure;
    }
    
    public function execute()
    {
        if ($this->getIntervenant()->getStructure() !== $this->structure 
                && $this->getIntervenant()->getStructure()->getParenteNiv2() !== $this->structure->getParenteNiv2()) {
            $this->setMessage(
                    sprintf("%s n'est pas affecté(e) à la structure (%s) ou à l'une de ses sous-structures.",
                            $this->getIntervenant(),
                            $this->structure));
            return false;
        }

        return true;
    }
    
    public function isRelevant()
    {
        return null !== $this->getIntervenant()->getStructure();
    }
}
