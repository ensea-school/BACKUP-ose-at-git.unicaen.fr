<?php

namespace Mission\Controller;

use Application\Service\ContextService;
use Mission\Form\MissionTauxForm;
use Mission\Service\MissionTauxService;
use Psr\Container\ContainerInterface;

/**
 * Description of MissionTauxControllerFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTauxControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionTauxController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTauxController
    {
        $controller = new MissionTauxController;
        $controller->setServiceMissionTaux($container->get(MissionTauxService::class));
        $controller->setServiceContext($container->get(ContextService::class));
        return $controller;
    }
}

