<?php

namespace Application\Service\Initializer;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Form\FormElementManager;
use Application\Service\Intervenant as IntervenantService;

/**
 * Transmet à une instance le service Intervenant, ssi sa classe implémente l'interface qui va bien.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see IntervenantServiceAwareInterface
 */
class IntervenantServiceAwareInitializer extends AbstractEntityServiceAwareInitializer
{
    protected $entityClassName = 'Intervenant';
    
    /**
     * Initialize
     *
     * @param $instance
     * @param ServiceLocatorInterface $serviceLocator
     */
//    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
//    {
//        if ($serviceLocator instanceof ControllerManager || $serviceLocator instanceof FormElementManager) {
//            $serviceLocator = $serviceLocator->getServiceLocator();
//        }
//        if ($instance instanceof IntervenantServiceAwareInterface && !$instance instanceof IntervenantService) {
//            $instance->setIntervenantService($serviceLocator->get('applicationIntervenantService'));
//        }
//    }
}