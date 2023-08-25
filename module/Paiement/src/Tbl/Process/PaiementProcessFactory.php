<?php

namespace Paiement\Tbl\Process;

use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\BddService;


/**
 * Description of PaiementProcessFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class PaiementProcessFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PaiementProcess
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PaiementProcess
    {
        $service = new PaiementProcess;

        $service->setServiceBdd($container->get(BddService::class));

        return $service;
    }
}