<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of MajExportsCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MajExportsCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MajExportsCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MajExportsCommand
    {
        $command = new MajExportsCommand;
        $command->setBdd($container->get(Bdd::class));


        /* Injectez vos dépendances ICI */

        return $command;
    }
}