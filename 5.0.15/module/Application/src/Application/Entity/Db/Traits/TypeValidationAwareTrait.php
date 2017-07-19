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
    /**
     * @var TypeValidation
     */
    private $typeValidation;





    /**
     * @param TypeValidation $typeValidation
     * @return self
     */
    public function setTypeValidation( TypeValidation $typeValidation = null )
    {
        $this->typeValidation = $typeValidation;
        return $this;
    }



    /**
     * @return TypeValidation
     */
    public function getTypeValidation()
    {
        return $this->typeValidation;
    }
}