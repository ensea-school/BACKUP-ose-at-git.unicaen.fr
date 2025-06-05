<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use UnicaenImport\Processus\ImportProcessus;


/**
 * Description of SynchronisationCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SynchronisationCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SynchronisationCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SynchronisationCommand
    {

        $command = new SynchronisationCommand;
        $command->setProcessusImport($container->get(ImportProcessus::class));

        /* Injectez vos dépendances ICI */

        return $command;
    }
}