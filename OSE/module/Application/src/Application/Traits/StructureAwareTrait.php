<?php

namespace Application\Traits;

use Application\Entity\Db\Structure;

/**
 * Description of StructureAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait StructureAwareTrait
{
    /**
     * @var Structure 
     */
    protected $structure;
    
    /**
     * Spécifie la structure concernée.
     * 
     * @param Structure $structure Structure concernée
     */
    public function setStructure(Structure $structure = null)
    {
        $this->structure = $structure;
        
        return $this;
    }
    
    /**
     * Retourne la structure concernée.
     * 
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }
}