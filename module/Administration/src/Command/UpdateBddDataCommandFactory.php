<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of UpdateBddDataCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateBddDataCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateBddDataCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateBddDataCommand
    {
        $command = new UpdateBddDataCommand;

        $command->setBdd($container->get(Bdd::class));

        return $command;
    }
}