<?php

namespace Application\Service\Traits;

use Application\Service\ServiceService;
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
     * @var ServiceService
     */
    private $service;





    /**
     * @param ServiceService $service
     *
*@return self
     */
    public function setServiceService(ServiceService $service )
    {
        $this->service = $service;
        return $this;
    }



    /**
     * @return ServiceService
     * @throws RuntimeException
     */
    public function getServiceService()
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