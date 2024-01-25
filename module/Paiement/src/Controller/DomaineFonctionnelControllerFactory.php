<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;

class DomaineFonctionnelControllerFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): DomaineFonctionnelController
    {
        $controller = new DomaineFonctionnelController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}