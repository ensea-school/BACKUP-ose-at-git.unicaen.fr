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



    /**
     * @param Validation $validation
     *
     * @return self
     */
    public function setValidation(?Validation $validation)
    {
        $this->validation = $validation;

        return $this;
    }



    public function getValidation(): ?Validation
    {
        return $this->validation;
    }
}