<?php

namespace Workflow\Tbl\Process;

use Psr\Container\ContainerInterface;



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

        /* Injectez vos dépendances ICI */

        return $service;
    }
}