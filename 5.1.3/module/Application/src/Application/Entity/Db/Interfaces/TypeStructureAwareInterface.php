<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeStructure;

/**
 * Description of TypeStructureAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeStructureAwareInterface
{
    /**
     * @param TypeStructure $typeStructure
     * @return self
     */
    public function setTypeStructure( TypeStructure $typeStructure = null );



    /**
     * @return TypeStructure
     */
    public function getTypeStructure();
}