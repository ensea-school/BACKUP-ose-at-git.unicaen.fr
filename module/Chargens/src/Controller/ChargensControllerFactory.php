<?php

namespace Chargens\Controller;

use Psr\Container\ContainerInterface;

class ChargensControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return ChargensController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new ChargensController();

        return $controller;
    }

}