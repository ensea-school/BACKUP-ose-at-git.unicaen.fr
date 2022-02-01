<?php

namespace Application\Controller\Factory;

use Application\Controller\DroitsController;
use Psr\Container\ContainerInterface;

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
        $doctrineCache = $container->get('doctrine.cache.filesystem');

        $controller = new DroitsController($doctrineCache);
        $controller->setServicePrivilege($container->get('UnicaenAuth\Service\Privilege'));

        return $controller;
    }

}