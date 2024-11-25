<?php

namespace Contrat\Service;

use Psr\Container\ContainerInterface;

use UnicaenSignature\Service\ProcessService;
use UnicaenSignature\Service\SignatureService;


/**
 * Description of ContratServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ContratServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContratService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ContratService
    {
        $service = new ContratService($container->get('config')['unicaen-signature'] ?? []);
        $service->setSignatureService($container->get(SignatureService::class));
        $service->setProcessService($container->get(ProcessService::class));

        return $service;
    }
}

