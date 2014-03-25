<?php

namespace Application\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of StructureFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureFactory implements FactoryInterface
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

        return new Structure($em);
    }
}