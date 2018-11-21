<?php

namespace Application\Controller\Factory;

use Application\Controller\EtatSortieController;
use Zend\Mvc\Controller\ControllerManager;

class EtatSortieControllerFactory
{
    /**
     * Create controller
     *
     * @param ControllerManager $controllerManager
     *
     * @return EtatSortieController
     */
    public function __invoke(ControllerManager $controllerManager)
    {
        $controller = new EtatSortieController();

        return $controller;
    }

}