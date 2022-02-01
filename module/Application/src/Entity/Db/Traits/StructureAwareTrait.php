<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Structure;

/**
 * Description of StructureAwareTrait
 *
 * @author UnicaenCode
 */
trait StructureAwareTrait
{
    protected ?Structure $structure = null;



    /**
     * @param Structure $structure
     *
     * @return self
     */
    public function setStructure( Structure $structure )
    {
        $this->structure = $structure;

        return $this;
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }
}