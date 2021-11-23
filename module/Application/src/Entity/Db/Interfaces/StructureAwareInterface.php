<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Structure;

/**
 * Description of StructureAwareInterface
 *
 * @author UnicaenCode
 */
interface StructureAwareInterface
{
    /**
     * @param Structure $structure
     * @return self
     */
    public function setStructure( Structure $structure = null );



    /**
     * @return Structure
     */
    public function getStructure();
}