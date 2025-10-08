<?php

namespace Chargens\Controller;

use Framework\Navigation\Navigation;
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
        $controller = new ChargensController(
            $container->get(Navigation::class),
        );

        return $controller;
    }

}