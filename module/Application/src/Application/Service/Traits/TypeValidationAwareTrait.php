<?php

namespace Application\Service\Traits;

use Application\Service\TypeValidation;

/**
 * Description of TypeValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeValidationAwareTrait
{
    /**
     * @var TypeValidation
     */
    private $serviceTypeValidation;



    /**
     * @param TypeValidation $serviceTypeValidation
     *
     * @return self
     */
    public function setServiceTypeValidation(TypeValidation $serviceTypeValidation)
    {
        $this->serviceTypeValidation = $serviceTypeValidation;

        return $this;
    }



    /**
     * @return TypeValidation
     */
    public function getServiceTypeValidation()
    {
        if (empty($this->serviceTypeValidation)) {
            $this->serviceTypeValidation = \Application::$container->get('ApplicationTypeValidation');
        }

        return $this->serviceTypeValidation;
    }
}