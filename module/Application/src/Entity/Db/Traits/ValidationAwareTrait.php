<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Validation;

/**
 * Description of ValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationAwareTrait
{
    protected ?Validation $validation;



    /**
     * @param Validation|null $validation
     *
     * @return self
     */
    public function setValidation( ?Validation $validation )
    {
        $this->validation = $validation;

        return $this;
    }



    public function getValidation(): ?Validation
    {
        return $this->validation;
    }
}