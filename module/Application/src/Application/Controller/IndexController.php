<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * 
 * @method \Application\Controller\Plugin\Intervenant intervenant() Description
 */
class IndexController extends AbstractActionController
{
    public function indexAction()
    {
//        var_dump($this->identity());
        return array();
    }

    public function demoAction()
    {
        return array();
    }
    
    public function searchAction()
    {
        if (($id = $this->params()->fromPost('id'))) {
            
            $intervenant = $this->intervenant()->getRepo()->find($id);
            
            $view = new \Zend\View\Model\ViewModel();
            $view->setVariables(array('intervenant' => $intervenant));
            $view->setTerminal($this->getRequest()->isXmlHttpRequest());

            return $view;
            
        }
        
        exit;
    }
}
