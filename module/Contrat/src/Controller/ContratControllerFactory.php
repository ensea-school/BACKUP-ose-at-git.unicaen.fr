<?php

namespace Contrat\Controller;

use Contrat\Service\ContratServiceListeService;
use Psr\Container\ContainerInterface;
use UnicaenSignature\Service\ProcessService;

class ContratControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return ContratController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new ContratController();
        $controller->setServiceContratServiceListe($container->get(ContratServiceListeService::class));
        $controller->setProcessService($container->get(ProcessService::class));

        return $controller;
    }

}