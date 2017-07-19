<?php

namespace Application\Service\Interfaces;

use Application\Service\Validation;
use RuntimeException;

/**
 * Description of ValidationAwareInterface
 *
 * @author UnicaenCode
 */
interface ValidationAwareInterface
{
    /**
     * @param Validation $serviceValidation
     * @return self
     */
    public function setServiceValidation( Validation $serviceValidation );



    /**
     * @return ValidationAwareInterface
     * @throws RuntimeException
     */
    public function getServiceValidation();
}