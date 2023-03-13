<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\TypeInterventionStructure;

/**
 * Description of TypeInterventionStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStructureAwareTrait
{
    protected ?TypeInterventionStructure $typeInterventionStructure = null;



    /**
     * @param TypeInterventionStructure|null $typeInterventionStructure
     *
     * @return self
     */
    public function setTypeInterventionStructure( ?TypeInterventionStructure $typeInterventionStructure )
    {
        $this->typeInterventionStructure = $typeInterventionStructure;

        return $this;
    }



    /**
     * @return TypeInterventionStructure|null
     */
    public function getTypeInterventionStructure(): ?TypeInterventionStructure
    {
        return $this->typeInterventionStructure;
    }
}