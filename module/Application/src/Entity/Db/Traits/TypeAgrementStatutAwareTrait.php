<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeAgrementStatut;

/**
 * Description of TypeAgrementStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementStatutAwareTrait
{
    protected ?TypeAgrementStatut $typeAgrementStatut;



    /**
     * @param TypeAgrementStatut|null $typeAgrementStatut
     *
     * @return self
     */
    public function setTypeAgrementStatut( ?TypeAgrementStatut $typeAgrementStatut )
    {
        $this->typeAgrementStatut = $typeAgrementStatut;

        return $this;
    }



    public function getTypeAgrementStatut(): ?TypeAgrementStatut
    {
        return $this->typeAgrementStatut;
    }
}