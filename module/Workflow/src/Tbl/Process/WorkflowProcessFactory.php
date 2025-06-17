<?php

namespace Workflow\Tbl\Process;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use UnicaenTbl\Service\BddService;


/**
 * Description of WorkflowProcessFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class WorkflowProcessFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return WorkflowProcess
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): WorkflowProcess
    {
        $service = new WorkflowProcess;

        $service->setServiceBdd($container->get(BddService::class));
        $service->setBdd($container->get(Bdd::class));

        return $service;
    }
}