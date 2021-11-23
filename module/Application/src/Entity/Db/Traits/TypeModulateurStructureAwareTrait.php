<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeModulateurStructure;

/**
 * Description of TypeModulateurStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurStructureAwareTrait
{
    /**
     * @var TypeModulateurStructure
     */
    private $typeModulateurStructure;





    /**
     * @param TypeModulateurStructure $typeModulateurStructure
     * @return self
     */
    public function setTypeModulateurStructure( TypeModulateurStructure $typeModulateurStructure = null )
    {
        $this->typeModulateurStructure = $typeModulateurStructure;
        return $this;
    }



    /**
     * @return TypeModulateurStructure
     */
    public function getTypeModulateurStructure()
    {
        return $this->typeModulateurStructure;
    }
}