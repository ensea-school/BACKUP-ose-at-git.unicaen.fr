<?php

namespace Lieu\Service;

use Psr\Container\ContainerInterface;


/**
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class AdresseNumeroComplServiceFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): AdresseNumeroComplService
    {
        $service = new AdresseNumeroComplService();

        return $service;
    }
}