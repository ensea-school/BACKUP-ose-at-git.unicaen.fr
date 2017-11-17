<?php

namespace Application\Service\Traits;

use Application\Service\DbEventService;

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
     *
     * @return self
     */
    public function setServiceDbEvent(DbEventService $serviceDbEvent)
    {
        $this->serviceDbEvent = $serviceDbEvent;

        return $this;
    }



    /**
     * @return DbEventService
     */
    public function getServiceDbEvent()
    {
        if (empty($this->serviceDbEvent)) {
            $this->serviceDbEvent = \Application::$container->get('dbEvent');
        }

        return $this->serviceDbEvent;
    }
}