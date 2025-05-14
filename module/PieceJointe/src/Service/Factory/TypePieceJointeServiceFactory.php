<?php

namespace PieceJointe\Service\Factory;

use Doctrine\ORM\EntityManager;
use PieceJointe\Service\TypePieceJointeService;
use Psr\Container\ContainerInterface;


class TypePieceJointeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypePieceJointeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypePieceJointeService
    {
        $service = new TypePieceJointeService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}