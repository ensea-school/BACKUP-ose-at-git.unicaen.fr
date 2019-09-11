<?php

namespace Application\Controller\Factory;

use Application\Controller\AdministrationController;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\ControllerManager;

class AdministrationControllerFactory
{
    /**
     * Create controller
     *
     * @param ControllerManager $controllerManager
     *
     * @return AdministrationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new AdministrationController();

        return $controller;
    }

}