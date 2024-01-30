<?php

namespace ContratProcess\Tbl\Process;

use Contrat\Tbl\Process\ContratProcess;
use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\BddService;


/**
 * Description of ContratProcessFactory
 *
 */
class ContratProcessFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContratProcess
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ContratProcess
    {
        $service = new ContratProcess;

        $service->setServiceBdd($container->get(BddService::class));

        return $service;
    }
}