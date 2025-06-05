<?php

namespace Application\Controller\Plugin;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

/**
 * Description of ContextFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ContextFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $context = new Context();

        $context->setEntityManager($container->get(EntityManager::class));

        return $context;
    }
}