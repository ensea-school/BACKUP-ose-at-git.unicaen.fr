<?php

namespace Application\Service;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Transmet à une instance le contexte si sa classe implémente l'interface qui va bien.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 * @see ContextAwareInterface
 */
class ContextAwareInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if (method_exists($serviceLocator, 'getServiceLocator')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        if ($instance instanceof ContextProviderAwareInterface && !$instance instanceof ContextProvider) {
            $instance->setContextProvider($serviceLocator->get('applicationContextProvider'));
        }
    }
}