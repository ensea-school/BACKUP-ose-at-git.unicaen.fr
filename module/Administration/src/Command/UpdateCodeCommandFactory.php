<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;



/**
 * Description of UpdateCodeCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateCodeCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateCodeCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateCodeCommand
    {
        $command = new UpdateCodeCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}