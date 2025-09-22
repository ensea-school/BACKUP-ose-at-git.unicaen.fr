<?php

namespace PieceJointe\Service\Factory;

use Doctrine\ORM\EntityManager;
use PieceJointe\Service\PieceJointeService;
use Psr\Container\ContainerInterface;


class PieceJointeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PieceJointeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PieceJointeService
    {
        $service = new PieceJointeService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}