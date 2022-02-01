<?php

namespace Application\Service\Traits;

use Application\Service\ValidationService;

/**
 * Description of ValidationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationServiceAwareTrait
{
    protected ?ValidationService $serviceValidation = null;



    /**
     * @param ValidationService $serviceValidation
     *
     * @return self
     */
    public function setServiceValidation( ?ValidationService $serviceValidation )
    {
        $this->serviceValidation = $serviceValidation;

        return $this;
    }



    public function getServiceValidation(): ?ValidationService
    {
        if (empty($this->serviceValidation)){
            $this->serviceValidation = \Application::$container->get(ValidationService::class);
        }

        return $this->serviceValidation;
    }
}