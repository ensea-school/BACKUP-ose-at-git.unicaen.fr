<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of UpdateBddCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateBddCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateBddCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateBddCommand
    {
        $command = new UpdateBddCommand;

        $command->setBdd($container->get(Bdd::class));

        return $command;
    }
}