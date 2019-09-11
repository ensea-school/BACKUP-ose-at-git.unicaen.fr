<?php

namespace Application\Processus\Factory;


use Application\Processus\IntervenantProcessus;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantProcessusFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $processus = new IntervenantProcessus;

        $processus->setEntityManager($container->get(\Application\Constants::BDD));

        return $processus;
    }
}