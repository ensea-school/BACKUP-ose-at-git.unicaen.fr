<?php

namespace Application\Controller\Factory;

use Application\Controller\ChargensController;
use Application\Controller\DossierController;
use Interop\Container\ContainerInterface;
use Zend\Mvc\Controller\ControllerManager;

class DossierControllerFactory
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
        $controller = new DossierController();

        return $controller;
    }

}