<?php

namespace Lieu\Service;

use Psr\Container\ContainerInterface;


/**
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PaysServiceFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): PaysService
    {
        $service = new PaysService();

        return $service;
    }
}