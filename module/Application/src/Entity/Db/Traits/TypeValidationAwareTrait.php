<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypeValidation;

/**
 * Description of TypeValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeValidationAwareTrait
{
    protected ?TypeValidation $typeValidation = null;



    /**
     * @param TypeValidation $typeValidation
     *
     * @return self
     */
    public function setTypeValidation( ?TypeValidation $typeValidation )
    {
        $this->typeValidation = $typeValidation;

        return $this;
    }



    public function getTypeValidation(): ?TypeValidation
    {
        return $this->typeValidation;
    }
}