<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;



/**
 * Description of InstallCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class InstallCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return InstallCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): InstallCommand
    {
        $command = new InstallCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}