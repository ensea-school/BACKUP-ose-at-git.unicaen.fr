<?php

namespace Formule\Command;

use Psr\Container\ContainerInterface;



/**
 * Description of BuildCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class BuildCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return BuildCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): BuildCommand
    {
        $command = new BuildCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}