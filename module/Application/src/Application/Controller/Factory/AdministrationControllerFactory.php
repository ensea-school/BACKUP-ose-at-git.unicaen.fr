<?php

namespace Application\Controller\Factory;

use Application\Controller\AdministrationController;
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
    public function __invoke(ControllerManager $controllerManager)
    {
        $controller = new AdministrationController();
        $controller->setUserService( $controllerManager->getServiceLocator()->get('UnicaenAuth\Service\User') );

        return $controller;
    }

}