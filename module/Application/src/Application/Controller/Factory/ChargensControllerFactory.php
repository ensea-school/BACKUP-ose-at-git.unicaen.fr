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
        $sl = $controllerManager->getServiceLocator();

        $controller = new ChargensController();

        $controller->setProviderChargens($sl->get('chargens'));
        //$controller->setServiceContext($sl->get('ApplicationContext'));
        //$controller->setServiceStructure($sl->get('ApplicationStructure'));
        //$controller->setServiceEtape($sl->get('ApplicationEtape'));

        return $controller;
    }

}