<?php

namespace Application\Service\Traits;

use Application\Service\TypeFormation;
use Common\Exception\RuntimeException;

trait TypeFormationAwareTrait
{
    /**
     * description
     *
     * @var TypeFormation
     */
    private $serviceTypeFormation;

    /**
     *
     * @param TypeFormation $serviceTypeFormation
     * @return self
     */
    public function setServiceTypeFormation( TypeFormation $serviceTypeFormation )
    {
        $this->serviceTypeFormation = $serviceTypeFormation;
        return $this;
    }

    /**
     *
     * @return TypeFormation
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeFormation()
    {
        if (empty($this->serviceTypeFormation)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeFormation');
        }else{
            return $this->serviceTypeFormation;
        }
    }

}