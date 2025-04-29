<?php

namespace PieceJointe\Tbl\Process;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use UnicaenTbl\Service\BddService;

/**
 * Description of PieceJointeFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PieceJointeProcessFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PieceJointeProcess
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): PieceJointeProcess
    {
        $service = new PieceJointeProcess();

        $service->setServiceBdd($container->get(BddService::class));
        $service->setBdd($container->get(Bdd::class));

        return $service;
    }
}
