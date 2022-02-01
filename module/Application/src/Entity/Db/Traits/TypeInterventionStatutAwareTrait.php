<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeInterventionStatut;

/**
 * Description of TypeInterventionStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutAwareTrait
{
    protected ?TypeInterventionStatut $typeInterventionStatut = null;



    /**
     * @param TypeInterventionStatut $typeInterventionStatut
     *
     * @return self
     */
    public function setTypeInterventionStatut( ?TypeInterventionStatut $typeInterventionStatut )
    {
        $this->typeInterventionStatut = $typeInterventionStatut;

        return $this;
    }



    public function getTypeInterventionStatut(): ?TypeInterventionStatut
    {
        return $this->typeInterventionStatut;
    }
}