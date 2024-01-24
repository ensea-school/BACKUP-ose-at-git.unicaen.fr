<?php

namespace Formule\Service;

use Psr\Container\ContainerInterface;

class FormuleResultatServiceFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): FormuleResultatService
    {
        $service = new FormuleResultatService;

        return $service;
    }
}