<?php

namespace Utilisateur\Controller;

use Psr\Container\ContainerInterface;
use Utilisateur\Service\PrivilegeService;

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
        $controller->setServicePrivilege($container->get(PrivilegeService::class));

        return $controller;
    }

}