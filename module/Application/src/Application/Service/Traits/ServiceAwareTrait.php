<?php

namespace Application\Service\Traits;

use Application\Service\Service;
use Common\Exception\RuntimeException;

trait ServiceAwareTrait
{
    /**
     * description
     *
     * @var Service
     */
    private $serviceService;

    /**
     *
     * @param Service $serviceService
     * @return self
     */
    public function setServiceService( Service $serviceService )
    {
        $this->serviceService = $serviceService;
        return $this;
    }

    /**
     *
     * @return Service
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceService()
    {
        if (empty($this->serviceService)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationService');
        }else{
            return $this->serviceService;
        }
    }

}