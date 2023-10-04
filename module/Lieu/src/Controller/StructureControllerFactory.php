<?php

namespace Lieu\Controller;

use Psr\Container\ContainerInterface;

class StructureControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new StructureController();

        return $controller;
    }
}