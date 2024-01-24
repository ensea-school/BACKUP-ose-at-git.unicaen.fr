<?php

namespace Intervenant\Processus;


use Psr\Container\ContainerInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantProcessusFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $processus = new IntervenantProcessus;

        $processus->setEntityManager($container->get(\Application\Constants::BDD));

        return $processus;
    }
}