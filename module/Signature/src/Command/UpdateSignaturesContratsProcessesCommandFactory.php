<?php

namespace Signature\Command;

use Psr\Container\ContainerInterface;
use UnicaenSignature\Service\ProcessService;


/**
 * Description of SignatureUpdateAllProcessesContratCommandFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class UpdateSignaturesContratsProcessesCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateSignaturesContratsProcessesCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateSignaturesContratsProcessesCommand
    {
        $command = new UpdateSignaturesContratsProcessesCommand;

        $command->setProcessService($container->get(ProcessService::class));

        /* Injectez vos dépendances ICI */

        return $command;
    }
}