<?php

namespace Lieu\Service;

use Psr\Container\ContainerInterface;


/**
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class StructureServiceFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): StructureService
    {
        $service = new StructureService();

        return $service;
    }
}