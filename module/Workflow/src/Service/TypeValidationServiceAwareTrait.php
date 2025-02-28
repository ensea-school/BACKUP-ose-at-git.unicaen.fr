<?php

namespace Workflow\Service;

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
    public function setServiceTypeValidation(?TypeValidationService $serviceTypeValidation)
    {
        $this->serviceTypeValidation = $serviceTypeValidation;

        return $this;
    }



    public function getServiceTypeValidation(): ?TypeValidationService
    {
        if (empty($this->serviceTypeValidation)) {
            $this->serviceTypeValidation = \AppAdmin::container()->get(TypeValidationService::class);
        }

        return $this->serviceTypeValidation;
    }
}