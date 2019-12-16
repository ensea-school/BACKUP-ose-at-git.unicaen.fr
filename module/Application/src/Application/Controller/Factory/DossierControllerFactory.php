<?php

namespace Application\Controller\Factory;

use Application\Controller\DossierController;
use Interop\Container\ContainerInterface;
use UnicaenAuth\Service\UserContext;

class DossierControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return DossierController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new DossierController();

        /** @var UserContext $userContextService */
        $userContextService = $container->get(UserContext::class);

        $controller->setServiceUserContext($userContextService);

        return $controller;
    }

}