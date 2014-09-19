<?php

namespace Application\Service\Workflow;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use Zend\Mvc\Controller\ControllerManager;

/**
 * Transmet à une instance le service de workflow dédié aux intervenants, 
 * ssi sa classe implémente l'interface qui va bien.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see WorkflowIntervenantAwareInterface
 */
class WorkflowIntervenantAwareInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof HelperPluginManager || $serviceLocator instanceof ControllerManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        if ($instance instanceof WorkflowIntervenantAwareInterface) {
            $instance->setWorkflowIntervenant($serviceLocator->get('WorkflowIntervenant'));
        }
    }
}