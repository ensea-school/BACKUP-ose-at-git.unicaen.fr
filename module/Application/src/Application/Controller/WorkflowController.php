<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;

/**
 * Description of WorkflowController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * Dessine le bouton pointant vers l'étape situé après l'étape correspondant à la route spécifié.
     * 
     * @return array
     */
    public function navNextAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            exit;
        }
        $role        = $this->getContextProvider()->getSelectedIdentityRole();
        $intervenant = $this->context()->intervenantFromRoute();
        $route       = $this->context()->routeFromQuery();
        $prepend     = $this->context()->prependFromQuery();
        if (!$intervenant) {
            exit;
        }
        
        return compact('intervenant', 'role', 'route', 'prepend');
    }
}