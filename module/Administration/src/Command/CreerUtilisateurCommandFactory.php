<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of CreerUtilisateurCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CreerUtilisateurCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CreerUtilisateurCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CreerUtilisateurCommand
    {
        $command = new CreerUtilisateurCommand;
        $command->setBdd($container->get(Bdd::class));

        /* Injectez vos dépendances ICI */

        return $command;
    }
}