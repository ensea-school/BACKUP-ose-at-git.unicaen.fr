<?php

namespace Application\Service\Traits;

use Application\Service\PilotageService;
use Application\Module;
use RuntimeException;

/**
 * Description of PilotageServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PilotageServiceAwareTrait
{
    /**
     * @var PilotageService
     */
    private $servicePilotage;





    /**
     * @param PilotageService $servicePilotage
     * @return self
     */
    public function setServicePilotage( PilotageService $servicePilotage )
    {
        $this->servicePilotage = $servicePilotage;
        return $this;
    }



    /**
     * @return PilotageService
     * @throws RuntimeException
     */
    public function getServicePilotage()
    {
        if (empty($this->servicePilotage)){
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
        $this->servicePilotage = $serviceLocator->get('ApplicationPilotage');
        }
        return $this->servicePilotage;
    }
}