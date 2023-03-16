<?php

namespace Agrement\Controller\Factory;

use Agrement\Controller\AgrementController;
use Psr\Container\ContainerInterface;


class AgrementControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return AgrementController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AgrementController
    {
        $controller = new AgrementController();

        return $controller;
    }

}