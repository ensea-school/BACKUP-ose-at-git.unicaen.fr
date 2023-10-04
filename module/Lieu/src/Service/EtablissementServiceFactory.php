<?php

namespace Lieu\Service;

use Psr\Container\ContainerInterface;


/**
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class EtablissementServiceFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): EtablissementService
    {
        $service = new EtablissementService();

        return $service;
    }
}