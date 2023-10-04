<?php

namespace Lieu\Controller;

use Psr\Container\ContainerInterface;

class VoirieControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new VoirieController();

        return $controller;
    }
}