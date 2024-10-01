<?php

namespace Contrat\Controller;

use Contrat\Service\ContratServiceListeService;
use Contrat\Service\TblContratService;
use Psr\Container\ContainerInterface;

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
        $controller->setServiceTblContrat($container->get(TblContratService::class));

        return $controller;
    }

}