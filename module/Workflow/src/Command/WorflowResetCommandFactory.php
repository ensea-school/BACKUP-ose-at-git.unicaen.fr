<?php

namespace Workflow\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of WorkflowResetCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class WorflowResetCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return WorkflowResetCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): WorkflowResetCommand
    {
        $command = new WorkflowResetCommand();

        $command->setBdd($container->get(Bdd::class));

        return $command;
    }
}