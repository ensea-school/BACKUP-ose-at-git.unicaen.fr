<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\TypeInterventionStatut;

/**
 * Description of TypeInterventionStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutAwareTrait
{
    protected ?TypeInterventionStatut $typeInterventionStatut = null;



    /**
     * @param TypeInterventionStatut|null $typeInterventionStatut
     *
     * @return self
     */
    public function setTypeInterventionStatut( ?TypeInterventionStatut $typeInterventionStatut )
    {
        $this->typeInterventionStatut = $typeInterventionStatut;

        return $this;
    }



    /**
     * @return TypeInterventionStatut|null
     */
    public function getTypeInterventionStatut(): ?TypeInterventionStatut
    {
        return $this->typeInterventionStatut;
    }
}