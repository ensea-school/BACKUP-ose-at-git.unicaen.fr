<?php

namespace Application\Service\Factory;

use UnicaenImport\Processus\ImportProcessus;
use Interop\Container\ContainerInterface;
use Application\Service\IntervenantService;



/**
 * Description of IntervenantServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return IntervenantService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new IntervenantService;
        $service->setProcessusImport($container->get(ImportProcessus::class));

        return $service;
    }
}