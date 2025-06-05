<?php

namespace Agrement\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;


class AgrementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AgrementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AgrementService
    {
        $service = new AgrementService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}