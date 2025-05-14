<?php

namespace Application\Provider\Resource;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ResourceProviderFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get(EntityManager::class);
        /* @var $em \Doctrine\ORM\EntityManager */

        $resourceProvider = new ResourceProvider();
        $resourceProvider->setEntityManager($em);

        return $resourceProvider;
    }
}