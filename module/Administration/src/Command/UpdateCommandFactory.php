<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;



/**
 * Description of UpdateCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateCommand
    {
        $command = new UpdateCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}