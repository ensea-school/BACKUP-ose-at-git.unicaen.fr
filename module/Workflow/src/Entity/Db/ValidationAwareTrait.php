<?php

namespace Workflow\Entity\Db;


/**
 * Description of ValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationAwareTrait
{
    protected ?Validation $validation = null;



    public function setValidation(?Validation $validation): self
    {
        $this->validation = $validation;

        return $this;
    }



    public function getValidation(): ?Validation
    {
        return $this->validation;
    }
}