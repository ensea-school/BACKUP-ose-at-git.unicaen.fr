<?php

namespace Administration\Controller;

use Psr\Container\ContainerInterface;

class ParametreControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return ParametreController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new ParametreController();

        return $controller;
    }

}