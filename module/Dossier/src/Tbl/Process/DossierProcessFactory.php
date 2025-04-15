<?php

namespace Dossier\Tbl\Process;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use UnicaenTbl\Service\BddService;

/**
 * Description of DossierProcessFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class DossierProcessFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DossierProcess
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): DossierProcess
    {
        $service = new DossierProcess;

        $service->setServiceBdd($container->get(BddService::class));
        $service->setBdd($container->get(Bdd::class));

        return $service;
    }
}
