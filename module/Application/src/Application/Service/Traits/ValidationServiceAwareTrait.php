<?php

namespace Application\Service\Traits;

use Application\Service\ValidationService;

/**
 * Description of ValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationServiceAwareTrait
{
    /**
     * @var ValidationService
     */
    private $serviceValidation;



    /**
     * @param ValidationService $serviceValidation
     *
     * @return self
     */
    public function setServiceValidation(ValidationService $serviceValidation)
    {
        $this->serviceValidation = $serviceValidation;

        return $this;
    }



    /**
     * @return ValidationService
     */
    public function getServiceValidation()
    {
        if (empty($this->serviceValidation)) {
            $this->serviceValidation = \Application::$container->get(ValidationService::class);
        }

        return $this->serviceValidation;
    }
}