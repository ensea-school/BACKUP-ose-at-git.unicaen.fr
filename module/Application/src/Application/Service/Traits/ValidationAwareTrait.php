<?php

namespace Application\Service\Traits;

use Application\Service\Validation;
use Common\Exception\RuntimeException;

trait ValidationAwareTrait
{
    /**
     * description
     *
     * @var Validation
     */
    private $serviceValidation;

    /**
     *
     * @param Validation $serviceValidation
     * @return self
     */
    public function setServiceValidation( Validation $serviceValidation )
    {
        $this->serviceValidation = $serviceValidation;
        return $this;
    }

    /**
     *
     * @return Validation
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceValidation()
    {
        if (empty($this->serviceValidation)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationValidation');
        }else{
            return $this->serviceValidation;
        }
    }

}