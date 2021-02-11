<?php

namespace Application\Controller\Factory;

use Application\Controller\DossierController;
use Application\Controller\IntervenantController;
use Psr\Container\ContainerInterface;
use UnicaenImport\Processus\ImportProcessus;
use UnicaenImport\Service\DifferentielService;

class IntervenantControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return IntervenantController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new IntervenantController();

        $controller->setProcessusImport($container->get(ImportProcessus::class));
        $controller->setServiceDifferentiel($container->get(DifferentielService::class));

        return $controller;
    }

}