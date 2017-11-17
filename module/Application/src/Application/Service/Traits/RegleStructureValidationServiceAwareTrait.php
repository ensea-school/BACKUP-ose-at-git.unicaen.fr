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
    /**
     * @var RegleStructureValidationService
     */
    private $serviceRegleStructureValidation;



    /**
     * @param RegleStructureValidationService $serviceRegleStructureValidation
     *
     * @return self
     */
    public function setServiceRegleStructureValidation(RegleStructureValidationService $serviceRegleStructureValidation)
    {
        $this->serviceRegleStructureValidation = $serviceRegleStructureValidation;

        return $this;
    }



    /**
     * @return RegleStructureValidationService
     */
    public function getServiceRegleStructureValidation()
    {
        if (empty($this->serviceRegleStructureValidation)) {
            $this->serviceRegleStructureValidation = \Application::$container->get('applicationRegleStructureValidation');
        }

        return $this->serviceRegleStructureValidation;
    }
}