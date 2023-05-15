<?php

namespace Paiement\Entity\Db;

/**
 * Description of TypeModulateurStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurStructureAwareTrait
{
    protected ?TypeModulateurStructure $typeModulateurStructure = null;



    /**
     * @param TypeModulateurStructure $typeModulateurStructure
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