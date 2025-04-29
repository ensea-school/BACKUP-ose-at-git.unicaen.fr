<?php

namespace Administration\Controller;

use Psr\Container\ContainerInterface;

class GestionControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return GestionController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new GestionController();

        return $controller;
    }

}