<?php

namespace Lieu\Controller;

use Psr\Container\ContainerInterface;

class DepartementControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new DepartementController();

        return $controller;
    }
}