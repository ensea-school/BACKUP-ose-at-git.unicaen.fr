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
    protected ?TypeModulateurStructure $typeModulateurStructure;



    /**
     * @param TypeModulateurStructure|null $typeModulateurStructure
     *
     * @return self
     */
    public function setTypeModulateurStructure( ?TypeModulateurStructure $typeModulateurStructure )
    {
        $this->typeModulateurStructure = $typeModulateurStructure;

        return $this;
    }



    public function getTypeModulateurStructure(): ?TypeModulateurStructure
    {
        return $this->typeModulateurStructure;
    }
}