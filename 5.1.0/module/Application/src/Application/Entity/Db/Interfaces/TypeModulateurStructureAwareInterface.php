<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeModulateurStructure;

/**
 * Description of TypeModulateurStructureAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeModulateurStructureAwareInterface
{
    /**
     * @param TypeModulateurStructure $typeModulateurStructure
     * @return self
     */
    public function setTypeModulateurStructure( TypeModulateurStructure $typeModulateurStructure = null );



    /**
     * @return TypeModulateurStructure
     */
    public function getTypeModulateurStructure();
}