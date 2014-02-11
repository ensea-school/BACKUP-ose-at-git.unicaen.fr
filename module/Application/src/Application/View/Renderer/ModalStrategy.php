<?php

namespace Application\View\Renderer;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\View\ViewEvent;

/**
 * Description of ModalStrategy
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ModalStrategy implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function __construct() {
        
    }

    /**
     * 
     * @param \Zend\Mvc\MvcEvent $event
     * @return type
     */
    public function functionName(ViewEvent $event)
    {        
        var_dump($event->getRenderer()); 
    }
    
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'functionName'), $priority);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
}