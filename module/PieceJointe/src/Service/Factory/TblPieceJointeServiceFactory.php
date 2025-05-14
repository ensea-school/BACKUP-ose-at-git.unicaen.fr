<?php

namespace PieceJointe\Service\Factory;

use Doctrine\ORM\EntityManager;
use PieceJointe\Service\TblPieceJointeService;
use Psr\Container\ContainerInterface;


class TblPieceJointeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TblPieceJointeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): Tbl
    {
        $service = new TblPieceJointeService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}