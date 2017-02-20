<?php

namespace Application\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ContextFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ContextFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sl = $serviceLocator->getServiceLocator();

        $context = new Context();

        $context->setEntityManager( $sl->get(\Application\Constants::BDD) );
        $context->setServiceIntervenant( $sl->get('applicationIntervenant') );

        return $context;
    }

}