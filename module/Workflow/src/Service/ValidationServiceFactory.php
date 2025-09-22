<?php

namespace Workflow\Service;

use Psr\Container\ContainerInterface;

class ValidationServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): ValidationService
    {
        $service = new ValidationService();

        return $service;
    }

}