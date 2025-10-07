<?php

namespace Dossier\Controller\Factory;

use Dossier\Controller\IntervenantDossierController;
use Dossier\Service\TblDossierService;
use Psr\Container\ContainerInterface;
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

        $controller->setProcessusImport($container->get(ImportProcessus::class));
        $controller->setServiceTblDossier($container->get(TblDossierService::class));

        return $controller;
    }

}