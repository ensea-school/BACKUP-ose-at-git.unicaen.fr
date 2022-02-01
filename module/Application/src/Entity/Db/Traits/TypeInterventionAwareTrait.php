<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeIntervention;

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