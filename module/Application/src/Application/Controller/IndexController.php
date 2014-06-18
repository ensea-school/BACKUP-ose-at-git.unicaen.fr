<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * 
 * @method \Application\Controller\Plugin\Intervenant intervenant() Description
 */
class IndexController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;
    
    /**
     * 
     * @return type
     */
    public function indexAction()
    {
        $view = new \Zend\View\Model\ViewModel(array(
            'annee' => $this->getContextProvider()->getGlobalContext()->getAnnee(),
            'role'  => $this->getContextProvider()->getSelectedIdentityRole(),
        ));
        
        return $view;
    }
}