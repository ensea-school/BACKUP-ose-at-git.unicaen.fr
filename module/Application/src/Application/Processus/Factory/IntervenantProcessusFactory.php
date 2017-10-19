<?php

namespace Application\Processus\Factory;


use Application\Processus\IntervenantProcessus;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantProcessusFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return IntervenantProcessus
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $processus = new IntervenantProcessus;

        $processus->setEntityManager($serviceLocator->get(\Application\Constants::BDD));
        $processus->setServiceContext($serviceLocator->get('applicationContext'));

        return $processus;
    }
}