<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeValidation;
use RuntimeException;

/**
 * Description of TypeValidationAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeValidationAwareInterface
{
    /**
     * @param TypeValidation $serviceTypeValidation
     * @return self
     */
    public function setServiceTypeValidation( TypeValidation $serviceTypeValidation );



    /**
     * @return TypeValidationAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeValidation();
}