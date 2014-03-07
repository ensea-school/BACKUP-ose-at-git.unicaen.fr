<?php

namespace Application\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of IntervenantFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceReferentielFactoryFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) /* @var $serviceLocator \Zend\Mvc\Controller\PluginManager */
    {
        $em = $serviceLocator->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        return new ServiceReferentiel($em);
    }
}