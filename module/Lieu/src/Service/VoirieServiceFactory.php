<?php

namespace Lieu\Service;

use Psr\Container\ContainerInterface;


/**
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class VoirieServiceFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): VoirieService
    {
        $service = new VoirieService();

        return $service;
    }
}