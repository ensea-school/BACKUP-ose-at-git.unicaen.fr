<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;



/**
 * Description of ChangementMotDePasseCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ChangementMotDePasseCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ChangementMotDePasseCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ChangementMotDePasseCommand
    {
        $command = new ChangementMotDePasseCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}