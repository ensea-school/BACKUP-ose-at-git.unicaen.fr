<?php

namespace Application\Service\Traits;

use Application\Service\TypeValidationService;

/**
 * Description of TypeValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeValidationServiceAwareTrait
{
    /**
     * @var TypeValidationService
     */
    private $serviceTypeValidation;



    /**
     * @param TypeValidationService $serviceTypeValidation
     *
     * @return self
     */
    public function setServiceTypeValidation(TypeValidationService $serviceTypeValidation)
    {
        $this->serviceTypeValidation = $serviceTypeValidation;

        return $this;
    }



    /**
     * @return TypeValidationService
     */
    public function getServiceTypeValidation()
    {
        if (empty($this->serviceTypeValidation)) {
            $this->serviceTypeValidation = \Application::$container->get(TypeValidationService::class);
        }

        return $this->serviceTypeValidation;
    }
}