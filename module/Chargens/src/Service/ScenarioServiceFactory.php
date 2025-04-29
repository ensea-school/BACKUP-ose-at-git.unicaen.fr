<?php

namespace Chargens\Service;

use Psr\Container\ContainerInterface;

class ScenarioServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new ScenarioService();

        return $service;
    }
}