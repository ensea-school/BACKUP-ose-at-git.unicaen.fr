<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of FichiersVersFilesytemCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FichiersVersFilesystemCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return FichiersVersFilesystemCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): FichiersVersFilesystemCommand
    {
        $command = new FichiersVersFilesystemCommand;
        $command->setBdd($container->get(Bdd::class));

        /* Injectez vos dépendances ICI */

        return $command;
    }
}