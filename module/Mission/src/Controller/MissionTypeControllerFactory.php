<?php

namespace Mission\Controller;

use Application\Service\ContextService;
use Mission\Service\MissionTypeService;
use Psr\Container\ContainerInterface;

/**
 * Description of MissionTypeControllerFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionTypeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTypeController
    {
        $controller = new MissionTypeController;
        $controller->setServiceMissionType($container->get(MissionTypeService::class));
        $controller->setServiceContext($container->get(ContextService::class));
        return $controller;
    }
}

