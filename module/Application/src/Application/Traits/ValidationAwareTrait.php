<?php

namespace Application\Traits;

use Application\Entity\Db\Validation;

/**
 * Description of ValidationAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait ValidationAwareTrait
{
    /**
     * @var Validation
     */
    protected $validation;

    /**
     * @param Validation $validation
     * @return self
     */
    public function setValidation(Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * @return Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }
}