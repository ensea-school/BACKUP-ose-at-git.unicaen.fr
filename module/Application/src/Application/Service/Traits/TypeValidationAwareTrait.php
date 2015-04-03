<?php

namespace Application\Service\Traits;

use Application\Service\TypeValidation;
use Common\Exception\RuntimeException;

trait TypeValidationAwareTrait
{
    /**
     * description
     *
     * @var TypeValidation
     */
    private $serviceTypeValidation;

    /**
     *
     * @param TypeValidation $serviceTypeValidation
     * @return self
     */
    public function setServiceTypeValidation( TypeValidation $serviceTypeValidation )
    {
        $this->serviceTypeValidation = $serviceTypeValidation;
        return $this;
    }

    /**
     *
     * @return TypeValidation
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeValidation()
    {
        if (empty($this->serviceTypeValidation)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeValidation');
        }else{
            return $this->serviceTypeValidation;
        }
    }

}