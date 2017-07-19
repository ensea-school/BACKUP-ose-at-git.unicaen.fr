<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeInterventionStructure;

/**
 * Description of TypeInterventionStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStructureAwareTrait
{
    /**
     * @var TypeInterventionStructure
     */
    private $typeInterventionStructure;





    /**
     * @param TypeInterventionStructure $typeInterventionStructure
     * @return self
     */
    public function setTypeInterventionStructure( TypeInterventionStructure $typeInterventionStructure = null )
    {
        $this->typeInterventionStructure = $typeInterventionStructure;
        return $this;
    }



    /**
     * @return TypeInterventionStructure
     */
    public function getTypeInterventionStructure()
    {
        return $this->typeInterventionStructure;
    }
}