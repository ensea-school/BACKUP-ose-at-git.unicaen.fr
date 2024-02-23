<?php

namespace Formule\Tbl\Process\Sub;

use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\BddService;


/**
 * Description of ServiceDataManagerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ServiceDataManagerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ServiceDataManager
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ServiceDataManager
    {
        $service = new ServiceDataManager();

        $service->setServiceBdd($container->get(BddService::class));

        return $service;
    }
}