<?php

namespace Application\Controller\Factory;

use Application\Controller\ChargensController;
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
    public function __invoke(ControllerManager $controllerManager)
    {
        $controller = new ChargensController();

        return $controller;
    }

}