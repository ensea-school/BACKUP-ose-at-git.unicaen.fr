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
    use \Application\Traits\WorkflowIntervenantAwareTrait;
    
    /**
     * 
     * @return type
     */
    public function indexAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $view = new \Zend\View\Model\ViewModel(array(
            'annee' => $this->getContextProvider()->getGlobalContext()->getAnnee(),
            'role'  => $role,
        ));
        
        return $view;
    }
    
    /**
     * 
     * @return type
     */
    public function gestionAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $view = new \Zend\View\Model\ViewModel(array(
            'annee' => $this->getContextProvider()->getGlobalContext()->getAnnee(),
            'role'  => $role,
            'title' => "Gestion",
        ));
        
        return $view;
    }
}