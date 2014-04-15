<?php

namespace Application\View\Renderer;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Renderer\RendererInterface;
use Zend\View\ViewEvent;

/**
 * Description of ModalStrategy
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ModalStrategy implements ListenerAggregateInterface
{
    use \Zend\EventManager\ListenerAggregateTrait;

    const PARAM_NAME = 'modal';
    
    protected $renderer;
    
    /**
     * 
     * @param \Zend\View\Renderer\RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Select the PhpRenderer; typically, this will be registered last or at
     * low priority.
     *
     * @param  ViewEvent $e
     * @return PhpRenderer
     */
    public function selectRenderer(ViewEvent $e)
    {
        return $this->renderer;
    }

    /**
     * Populate the response object from the View
     *
     * Populates the content of the response object from the view rendering
     * results.
     *
     * @param ViewEvent $e
     * @return void
     */
    public function injectResponse(ViewEvent $e)
    {
        $modal = (bool) $e->getRequest()->getQuery(
                self::PARAM_NAME, 
                $e->getRequest()->getPost(self::PARAM_NAME, 0));
//        var_dump(__METHOD__, $modal, $e->getModel()/*, $e->getTraceAsString()*/);
     
        $renderer = $e->getRenderer();
        if ($renderer !== $this->renderer) {
            return;
        }

        $model = $e->getModel();

        $f = new \UnicaenApp\Filter\ModalInnerViewModel("Test modale", false);
        
        $modalViewModel = $f->filter($model);
        $modalViewModel->setTerminal($e->getRequest()->isXmlHttpRequest()); // Turn off the layout for AJAX requests
        
        $e->setModel($modalViewModel);
        
//        // Set content
//        // If content is empty, check common placeholders to determine if they are
//        // populated, and set the content from them.
//        if (empty($result)) {
//            $placeholders = $renderer->plugin('placeholder');
//            foreach ($this->contentPlaceholders as $placeholder) {
//                if ($placeholders->containerExists($placeholder)) {
//                    $result = (string) $placeholders->getContainer($placeholder);
//                    break;
//                }
//            }
//        }
//        $response->setContent($result);
    }
    
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events, $priority = 10)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, array($this, 'selectRenderer'), $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'injectResponse'), $priority);
    }
}