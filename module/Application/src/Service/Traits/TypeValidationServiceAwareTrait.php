<?php

namespace Application\Service\Traits;

use Application\Service\TypeValidationService;

/**
 * Description of TypeValidationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeValidationServiceAwareTrait
{
    protected ?TypeValidationService $serviceTypeValidation = null;



    /**
     * @param TypeValidationService $serviceTypeValidation
     *
     * @return self
     */
    public function setServiceTypeValidation( ?TypeValidationService $serviceTypeValidation )
    {
        $this->serviceTypeValidation = $serviceTypeValidation;

        return $this;
    }



    public function getServiceTypeValidation(): ?TypeValidationService
    {
        if (empty($this->serviceTypeValidation)){
            $this->serviceTypeValidation = \Application::$container->get(TypeValidationService::class);
        }

        return $this->serviceTypeValidation;
    }
}