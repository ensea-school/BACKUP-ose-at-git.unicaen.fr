<?php

namespace Application\Service\Traits;

use Application\Service\DbEventService;
use Application\Module;
use RuntimeException;

/**
 * Description of DbEventServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DbEventServiceAwareTrait
{
    /**
     * @var DbEventService
     */
    private $serviceDbEvent;





    /**
     * @param DbEventService $serviceDbEvent
     * @return self
     */
    public function setServiceDbEvent( DbEventService $serviceDbEvent )
    {
        $this->serviceDbEvent = $serviceDbEvent;
        return $this;
    }



    /**
     * @return DbEventService
     * @throws RuntimeException
     */
    public function getServiceDbEvent()
    {
        if (empty($this->serviceDbEvent)){
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
            $this->serviceDbEvent = $serviceLocator->get('dbEvent');
        }
        return $this->serviceDbEvent;
    }
}