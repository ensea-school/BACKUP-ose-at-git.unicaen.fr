<?php

namespace Contrat\Controller;

use Contrat\Service\ContratServiceListeService;
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

        $renderer = $container->get('ViewRenderer');

        $controller = new ContratController($renderer);
        $controller->setServiceContratServiceListe($container->get(ContratServiceListeService::class));

        return $controller;
    }

}