<?php

namespace PieceJointe\Service\Factory;

use Application\Constants;
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
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TblPieceJointeService
    {
        $service = new TblPieceJointeService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}