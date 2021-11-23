<?php

namespace Application\Controller\Factory;

use Application\Controller\AdministrationController;
use Psr\Container\ContainerInterface;

class AdministrationControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return AdministrationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new AdministrationController();

        return $controller;
    }

}