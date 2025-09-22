<?php

namespace PieceJointe\Controller\Factory;

use PieceJointe\Controller\PieceJointeController;
use PieceJointe\Service\TblPieceJointeService;
use Psr\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;

class PieceJointeControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return PieceJointeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new PieceJointeController();

        $controller->setMailService($container->get(MailService::class));
        $controller->setServiceTblPieceJointe($container->get(TblPieceJointeService::class));

        return $controller;
    }

}
