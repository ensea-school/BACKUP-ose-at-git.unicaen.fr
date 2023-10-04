<?php

namespace Lieu\Controller;

use Psr\Container\ContainerInterface;

class PaysControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new PaysController();

        return $controller;
    }
}