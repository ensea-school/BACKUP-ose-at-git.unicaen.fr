<?php

namespace Utilisateur\Controller;

use Unicaen\Framework\User\UserManager;
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
        $controller = new DroitsController(
            $container->get(UserManager::class),
        );
        $controller->setServicePrivilege($container->get(PrivilegeService::class));

        return $controller;
    }

}