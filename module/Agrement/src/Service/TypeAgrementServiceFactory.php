<?php

namespace Agrement\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;


class TypeAgrementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeAgrementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeAgrementService
    {
        $service = new TypeAgrementService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}