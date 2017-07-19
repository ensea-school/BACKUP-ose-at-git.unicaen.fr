<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeStructure;

/**
 * Description of TypeStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeStructureAwareTrait
{
    /**
     * @var TypeStructure
     */
    private $typeStructure;





    /**
     * @param TypeStructure $typeStructure
     * @return self
     */
    public function setTypeStructure( TypeStructure $typeStructure = null )
    {
        $this->typeStructure = $typeStructure;
        return $this;
    }



    /**
     * @return TypeStructure
     */
    public function getTypeStructure()
    {
        return $this->typeStructure;
    }
}