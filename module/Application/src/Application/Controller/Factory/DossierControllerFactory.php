<?php

namespace Application\Controller\Factory;

use Application\Controller\DossierController;
use Interop\Container\ContainerInterface;

class DossierControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return DossierController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new DossierController();

        return $controller;
    }

}