<?php

namespace OffreFormation\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of MajTauxMixiteCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MajTauxMixiteCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MajTauxMixiteCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MajTauxMixiteCommand
    {
        $command = new MajTauxMixiteCommand;
        $command->setBdd($container->get(Bdd::class));

        /* Injectez vos dépendances ICI */

        return $command;
    }
}