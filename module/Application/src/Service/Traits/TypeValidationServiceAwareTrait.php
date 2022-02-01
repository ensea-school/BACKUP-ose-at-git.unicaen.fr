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
    protected ?TypeValidationService $serviceTypeValidation;



    /**
     * @param TypeValidationService|null $serviceTypeValidation
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
        if (!$this->serviceTypeValidation){
            $this->serviceTypeValidation = \Application::$container->get(TypeValidationService::class);
        }

        return $this->serviceTypeValidation;
    }
}