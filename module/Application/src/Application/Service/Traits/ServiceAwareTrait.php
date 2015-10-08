<?php

namespace Application\Service\Traits;

use Application\Service\Service;
use Application\Module;
use RuntimeException;

/**
 * Description of ServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAwareTrait
{
    /**
     * @var Service
     */
    private $service;





    /**
     * @param Service $service
     * @return self
     */
    public function setService( Service $service )
    {
        $this->service = $service;
        return $this;
    }



    /**
     * @return Service
     * @throws RuntimeException
     */
    public function getService()
    {
        if (empty($this->service)){
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
        $this->service = $serviceLocator->get('ApplicationService');
        }
        return $this->service;
    }
}