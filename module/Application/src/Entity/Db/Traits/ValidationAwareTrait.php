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
    /**
     * @var Validation
     */
    private $validation;





    /**
     * @param Validation $validation
     * @return self
     */
    public function setValidation( Validation $validation = null )
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