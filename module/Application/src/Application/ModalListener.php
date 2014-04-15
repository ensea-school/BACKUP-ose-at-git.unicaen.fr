<?php

namespace Application;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

/**
 * Scrute l'événement déclenché juste avant que l'entité utilisateur ne soit persistée
 * pour renseigner les relations 'intervenant' et 'personnel'.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ModalListener implements ListenerAggregateInterface
{
    use \Zend\EventManager\ListenerAggregateTrait;

    const PARAM_NAME = 'modal';

    /**
     * 
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function functionName(MvcEvent $e)
    {
        $modal = (bool) $e->getRequest()->getQuery(self::PARAM_NAME, $e->getRequest()->getPost(self::PARAM_NAME, 0));

        if (!$modal) {
            return;
        }
        
        $viewModel = $e->getViewModel();
        
        $viewModel->clearChildren();
        
        $result = $e->getResult();
        
        if (is_array($result)) {
            $result = new \Zend\View\Model\ViewModel($result);
        }
        elseif (empty($result)) {
            $result = new \Zend\View\Model\ViewModel();
        }
        
        $title         = "Test modale";
        $displaySubmit = false;
        
        if (!$e->getRequest()->isXmlHttpRequest()) {
            $f = new \UnicaenApp\Filter\ModalViewModel($title, $displaySubmit);
        }
        else {
            $f = new \UnicaenApp\Filter\ModalInnerViewModel($title, $displaySubmit);
        }
        $modalViewModel = $f->filter($result);
        
        $viewModel->addChild($modalViewModel, 'content');
        
//        $e->setResult($modalViewModel);
    }
    
    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->getSharedManager()->attach(
//        $this->listeners[] = $events->getSharedManager()->attach(
//                'Zend\Mvc\Controller\AbstractActionController',
                MvcEvent::EVENT_DISPATCH, 
                array($this, 'functionName'), 
                1);
    }
}