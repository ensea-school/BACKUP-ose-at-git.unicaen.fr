<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;



/**
 * Description of UpdateStructuresCommandFactory
 *
 */
class UpdateStructuresCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateStructuresCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateStructuresCommand
    {
        $command = new UpdateStructuresCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}