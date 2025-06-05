<?php

namespace PieceJointe\Service\Factory;

use Doctrine\ORM\EntityManager;
use PieceJointe\Service\TypePieceJointeStatutService;
use Psr\Container\ContainerInterface;


class TypePieceJointeStatutServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypePieceJointeStatutService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypePieceJointeStatutService
    {
        $service = new TypePieceJointeStatutService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}