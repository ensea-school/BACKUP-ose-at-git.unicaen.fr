<?php

namespace Dossier\Controller\Factory;

use Dossier\Controller\EmployeurController;
use Psr\Container\ContainerInterface;


class EmployeurControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return EmployeurController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new EmployeurController();

        return $controller;
    }

}