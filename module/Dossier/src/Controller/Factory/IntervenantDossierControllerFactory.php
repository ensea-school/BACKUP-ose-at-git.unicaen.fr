<?php

namespace Dossier\Controller\Factory;

use Dossier\Controller\IntervenantDossierController;
use Psr\Container\ContainerInterface;
use UnicaenAuth\Service\UserContext;
use UnicaenImport\Processus\ImportProcessus;

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

        $controller->setProcessusImport($container->get(ImportProcessus::class));

        return $controller;
    }

}