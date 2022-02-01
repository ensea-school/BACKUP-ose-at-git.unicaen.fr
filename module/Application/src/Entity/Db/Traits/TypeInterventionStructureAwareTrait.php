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
    protected ?TypeInterventionStructure $typeInterventionStructure = null;



    /**
     * @param TypeInterventionStructure $typeInterventionStructure
     *
     * @return self
     */
    public function setTypeInterventionStructure( TypeInterventionStructure $typeInterventionStructure )
    {
        $this->typeInterventionStructure = $typeInterventionStructure;

        return $this;
    }



    public function getTypeInterventionStructure(): ?TypeInterventionStructure
    {
        return $this->typeInterventionStructure;
    }
}