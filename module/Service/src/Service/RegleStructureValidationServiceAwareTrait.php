<?php

namespace Service\Service;

/**
 * Description of RegleStructureValidationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait RegleStructureValidationServiceAwareTrait
{
    protected ?RegleStructureValidationService $serviceRegleStructureValidation = null;



    /**
     * @param RegleStructureValidationService $serviceRegleStructureValidation
     *
     * @return self
     */
    public function setServiceRegleStructureValidation(?RegleStructureValidationService $serviceRegleStructureValidation)
    {
        $this->serviceRegleStructureValidation = $serviceRegleStructureValidation;

        return $this;
    }



    public function getServiceRegleStructureValidation(): ?RegleStructureValidationService
    {
        if (empty($this->serviceRegleStructureValidation)) {
            $this->serviceRegleStructureValidation = \OseAdmin::instance()->container()->get(RegleStructureValidationService::class);
        }

        return $this->serviceRegleStructureValidation;
    }
}