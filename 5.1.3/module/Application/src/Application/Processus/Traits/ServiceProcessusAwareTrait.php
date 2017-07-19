<?php

namespace Application\Processus\Traits;

use Application\Processus\ServiceProcessus;
use Application\Module;
use RuntimeException;

/**
 * Description of ServiceProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceProcessusAwareTrait
{
    /**
     * @var ServiceProcessus
     */
    private $processusService;





    /**
     * @param ServiceProcessus $processusService
     * @return self
     */
    public function setProcessusService( ServiceProcessus $processusService )
    {
        $this->processusService = $processusService;
        return $this;
    }



    /**
     * @return ServiceProcessus
     * @throws RuntimeException
     */
    public function getProcessusService()
    {
        if (empty($this->processusService)){
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
            $this->processusService = $serviceLocator->get('processusService');
        }
        return $this->processusService;
    }
}