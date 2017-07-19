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
    /**
     * @var Structure
     */
    private $structure;





    /**
     * @param Structure $structure
     * @return self
     */
    public function setStructure( Structure $structure = null )
    {
        $this->structure = $structure;
        return $this;
    }



    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }
}