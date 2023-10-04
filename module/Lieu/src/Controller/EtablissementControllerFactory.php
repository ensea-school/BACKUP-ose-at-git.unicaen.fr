<?php

namespace Lieu\Controller;

use Psr\Container\ContainerInterface;

class EtablissementControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new EtablissementController();

        return $controller;
    }
}