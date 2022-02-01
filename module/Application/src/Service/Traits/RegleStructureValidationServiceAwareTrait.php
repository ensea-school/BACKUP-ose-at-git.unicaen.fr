<?php

namespace Application\Service\Traits;

use Application\Service\RegleStructureValidationService;

/**
 * Description of RegleStructureValidationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait RegleStructureValidationServiceAwareTrait
{
    protected ?RegleStructureValidationService $serviceRegleStructureValidation;



    /**
     * @param RegleStructureValidationService|null $serviceRegleStructureValidation
     *
     * @return self
     */
    public function setServiceRegleStructureValidation( ?RegleStructureValidationService $serviceRegleStructureValidation )
    {
        $this->serviceRegleStructureValidation = $serviceRegleStructureValidation;

        return $this;
    }



    public function getServiceRegleStructureValidation(): ?RegleStructureValidationService
    {
        if (!$this->serviceRegleStructureValidation){
            $this->serviceRegleStructureValidation = \Application::$container->get(RegleStructureValidationService::class);
        }

        return $this->serviceRegleStructureValidation;
    }
}