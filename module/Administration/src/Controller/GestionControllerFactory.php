<?php

namespace Administration\Controller;

use Unicaen\Framework\Navigation\Navigation;
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
        $controller = new GestionController(
            $container->get(Navigation::class),
        );

        return $controller;
    }

}