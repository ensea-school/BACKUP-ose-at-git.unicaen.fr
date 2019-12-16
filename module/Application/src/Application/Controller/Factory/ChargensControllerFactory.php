<?php

namespace Application\Controller\Factory;

use Application\Controller\ChargensController;
use Interop\Container\ContainerInterface;

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