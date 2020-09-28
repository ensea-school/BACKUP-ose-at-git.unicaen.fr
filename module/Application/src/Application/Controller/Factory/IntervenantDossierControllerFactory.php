<?php

namespace Application\Controller\Factory;

use Application\Controller\DossierController;
use Application\Controller\IntervenantDossierController;
use Psr\Container\ContainerInterface;
use UnicaenAuth\Service\UserContext;

class IntervenantDossierControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return IntervenantDossierController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new IntervenantDossierController();

        /** @var UserContext $userContextService */
        $userContextService = $container->get(UserContext::class);

        $controller->setServiceUserContext($userContextService);

        return $controller;
    }

}