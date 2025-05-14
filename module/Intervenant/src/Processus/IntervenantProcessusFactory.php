<?php

namespace Intervenant\Processus;


use Doctrine\ORM\EntityManager;
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

        $processus->setEntityManager($container->get(EntityManager::class));

        return $processus;
    }
}