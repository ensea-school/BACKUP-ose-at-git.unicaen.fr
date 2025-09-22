<?php

namespace Workflow\Service;

use Psr\Container\ContainerInterface;

class TypeValidationServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TypeValidationService
    {
        $service = new TypeValidationService();

        return $service;
    }

}