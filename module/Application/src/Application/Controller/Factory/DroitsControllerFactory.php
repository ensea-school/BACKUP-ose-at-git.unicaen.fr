<?php

namespace Application\Controller\Factory;

use Application\Controller\DroitsController;
use Interop\Container\ContainerInterface;

class DroitsControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return DroitsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new DroitsController();
        $controller->setServicePrivilege($container->get('UnicaenAuth\Service\Privilege'));

        return $controller;
    }

}