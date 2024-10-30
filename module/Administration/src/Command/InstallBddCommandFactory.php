<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of InstallBddCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class InstallBddCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return InstallBddCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): InstallBddCommand
    {
        $command = new InstallBddCommand;
        $command->setBdd($container->get(Bdd::class));

        return $command;
    }
}