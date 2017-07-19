<?php

namespace Application\Service\Traits;

use Application\Service\WfEtapeDepService;
use Application\Module;
use RuntimeException;

/**
 * Description of WfEtapeDepServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeDepServiceAwareTrait
{
    /**
     * @var WfEtapeDepService
     */
    private $serviceWfEtapeDep;





    /**
     * @param WfEtapeDepService $serviceWfEtapeDep
     * @return self
     */
    public function setServiceWfEtapeDep( WfEtapeDepService $serviceWfEtapeDep )
    {
        $this->serviceWfEtapeDep = $serviceWfEtapeDep;
        return $this;
    }



    /**
     * @return WfEtapeDepService
     * @throws RuntimeException
     */
    public function getServiceWfEtapeDep()
    {
        if (empty($this->serviceWfEtapeDep)){
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
            $this->serviceWfEtapeDep = $serviceLocator->get('applicationWfEtapeDep');
        }
        return $this->serviceWfEtapeDep;
    }
}