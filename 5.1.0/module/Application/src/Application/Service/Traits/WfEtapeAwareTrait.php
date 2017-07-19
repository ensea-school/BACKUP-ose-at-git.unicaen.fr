<?php

namespace Application\Service\Traits;

use Application\Service\WfEtape;
use Application\Module;
use RuntimeException;

/**
 * Description of WfEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeAwareTrait
{
    /**
     * @var WfEtape
     */
    private $serviceWfEtape;





    /**
     * @param WfEtape $serviceWfEtape
     * @return self
     */
    public function setServiceWfEtape( WfEtape $serviceWfEtape )
    {
        $this->serviceWfEtape = $serviceWfEtape;
        return $this;
    }



    /**
     * @return WfEtape
     * @throws RuntimeException
     */
    public function getServiceWfEtape()
    {
        if (empty($this->serviceWfEtape)){
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
        $this->serviceWfEtape = $serviceLocator->get('applicationWfEtape');
        }
        return $this->serviceWfEtape;
    }
}