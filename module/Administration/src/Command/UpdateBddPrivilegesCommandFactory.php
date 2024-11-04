<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of UpdateBddPrivilegesCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateBddPrivilegesCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateBddPrivilegesCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateBddPrivilegesCommand
    {
        $command = new UpdateBddPrivilegesCommand;

        $command->setBdd($container->get(Bdd::class));

        return $command;
    }
}