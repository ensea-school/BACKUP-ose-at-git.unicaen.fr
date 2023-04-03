<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\TypeIntervention;

/**
 * Description of TypeInterventionAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionAwareTrait
{
    protected ?TypeIntervention $typeIntervention = null;



    /**
     * @param TypeIntervention $typeIntervention
     *
     * @return self
     */
    public function setTypeIntervention( ?TypeIntervention $typeIntervention )
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }



    public function getTypeIntervention(): ?TypeIntervention
    {
        return $this->typeIntervention;
    }
}