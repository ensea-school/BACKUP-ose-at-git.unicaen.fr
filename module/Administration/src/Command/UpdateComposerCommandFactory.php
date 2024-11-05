<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;



/**
 * Description of UpdateComposerCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateComposerCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateComposerCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateComposerCommand
    {
        $command = new UpdateComposerCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}