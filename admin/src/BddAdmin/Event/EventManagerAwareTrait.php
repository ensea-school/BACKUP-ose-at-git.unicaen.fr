<?php

namespace BddAdmin\Event;

trait EventManagerAwareTrait
{
    /**
     * @var EventManager
     */
    private $eventManager;



    /**
     * @return EventManager
     */
    public function getEventManager(): EventManager
    {
        if ($this->eventManager) {
            return $this->eventManager;
        } else {
            return EventManager::getMain();
        }
    }



    /**
     * @param EventManager $eventManager
     *
     * @return EventManagerAwareTrait
     */
    public function setEventManager(EventManager $eventManager): EventManagerAwareTrait
    {
        $this->eventManager = $eventManager;

        return $this;
    }



    /**
     * @param string|null $action
     * @param null        $data
     *
     * @return Event
     */
    public function sendEvent(?string $action = null, $data = null): Event
    {
        if ($action === null) {
            $trace = debug_backtrace();
            if (isset($trace[1])) {
                $action     = $trace[1]['function'];
                $method     = new \ReflectionMethod($trace[1]['class'], $action);
                $parameters = $method->getParameters();
                if ($data === null) {
                    $data = [];
                    foreach ($parameters as $i => $parameter) {
                        if (isset($trace[1]['args'][$i])) {
                            $data[$parameter->getName()] = $trace[1]['args'][$i];
                        }
                    }
                }
            }
        }

        return $this->getEventManager()->sendEvent($this, $action, $data);
    }
}