<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TypeValidation;

/**
 * Description of TypeValidationAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeValidationAwareInterface
{
    /**
     * @param TypeValidation $typeValidation
     * @return self
     */
    public function setTypeValidation( TypeValidation $typeValidation = null );



    /**
     * @return TypeValidation
     */
    public function getTypeValidation();
}