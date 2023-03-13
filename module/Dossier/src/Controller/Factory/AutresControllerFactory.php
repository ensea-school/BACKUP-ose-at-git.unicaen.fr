<?php

namespace Dossier\Controller\Factory;

use Dossier\Controller\AutresController;
use Psr\Container\ContainerInterface;


class AutresControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return AutresController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new AutresController();

        return $controller;
    }

}