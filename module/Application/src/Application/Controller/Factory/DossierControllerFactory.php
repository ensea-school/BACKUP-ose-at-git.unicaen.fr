<?php

namespace Application\Controller\Factory;

use Application\Controller\ChargensController;
use Application\Controller\DossierController;
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
    public function __invoke(ControllerManager $controllerManager)
    {
        $controller = new DossierController();

        return $controller;
    }

}