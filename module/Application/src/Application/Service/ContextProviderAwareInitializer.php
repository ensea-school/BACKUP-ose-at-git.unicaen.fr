<?php

namespace Application\Service;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Form\FormElementManager;

/**
 * Transmet à une instance le fournisseur de contexte si sa classe implémente l'interface qui va bien.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see ContextProviderAwareInterface
 */
class ContextProviderAwareInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ControllerManager || $serviceLocator instanceof FormElementManager) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        if ($instance instanceof ContextProviderAwareInterface && !$instance instanceof ContextProvider) {
            $instance->setContextProvider($serviceLocator->get('applicationContextProvider'));
        }
    }
}