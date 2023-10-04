<?php

namespace Lieu\Service;

use Psr\Container\ContainerInterface;


/**
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DepartementServiceFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): DepartementService
    {
        $service = new DepartementService();

        return $service;
    }
}