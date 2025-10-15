<?php

namespace Intervenant\Controller;

use Psr\Container\ContainerInterface;
use Unicaen\Framework\Navigation\Navigation;
use UnicaenImport\Processus\ImportProcessus;
use UnicaenImport\Service\DifferentielService;

class IntervenantControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new IntervenantController(
            $container->get(Navigation::class),
        );

        $controller->setProcessusImport($container->get(ImportProcessus::class));
        $controller->setServiceDifferentiel($container->get(DifferentielService::class));

        return $controller;
    }

}