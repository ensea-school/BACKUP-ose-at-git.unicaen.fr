<?php

namespace Application\Service\Initializer;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Form\FormElementManager;

/**
 * CLasse mère des initialisateurs transmettant à une instance le service d'entity, 
 * ssi sa classe implémente l'interface qui va bien.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see IntervenantServiceAwareInterface
 */
abstract class AbstractEntityServiceAwareInitializer implements InitializerInterface
{
    protected $entityClassName;
    
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        $interfaceClassName     = "Application\\Service\\Initializer\\{$this->entityClassName}ServiceAwareInterface";
        $entityServiceClassName = "{$this->entityClassName}";
        $entityServiceName      = "application{$this->entityClassName}";
        $setter                 = "set{$this->entityClassName}Service";
        
        if (is_object($instance) && in_array($interfaceClassName, class_implements($instance)) && !is_a($instance, $entityServiceClassName)) {
            if ($serviceLocator instanceof ControllerManager || $serviceLocator instanceof FormElementManager) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
            $instance->$setter($serviceLocator->get($entityServiceName));
        }
    }
}