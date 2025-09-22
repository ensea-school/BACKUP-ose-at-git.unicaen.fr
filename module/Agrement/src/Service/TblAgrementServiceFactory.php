<?php

namespace Agrement\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;


class TblAgrementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TblAgrementService
     *
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TblAgrementService
    {
        $service = new TblAgrementService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}