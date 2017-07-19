<?php

namespace Application\Service\Traits;

use Application\Service\TypeValidation;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceTypeValidation( TypeValidation $serviceTypeValidation )
    {
        $this->serviceTypeValidation = $serviceTypeValidation;
        return $this;
    }



    /**
     * @return TypeValidation
     * @throws RuntimeException
     */
    public function getServiceTypeValidation()
    {
        if (empty($this->serviceTypeValidation)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceTypeValidation = $serviceLocator->get('ApplicationTypeValidation');
        }
        return $this->serviceTypeValidation;
    }
}