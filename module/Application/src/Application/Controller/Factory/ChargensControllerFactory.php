<?php

namespace Application\Controller\Factory;

use Application\Controller\ChargensController;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\ControllerManager;

class ChargensControllerFactory
{
    /**
     * Create controller
     *
     * @param ControllerManager $controllerManager
     *
     * @return ChargensController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new ChargensController();

        return $controller;
    }

}