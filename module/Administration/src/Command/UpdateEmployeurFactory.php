<?php

namespace Administration\Command;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of UpdateEmployeurFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateEmployeurFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UpdateEmployeur
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): UpdateEmployeur
    {
        $command = new UpdateEmployeur;
        $command->setBdd($container->get(Bdd::class));


        /* Injectez vos dépendances ICI */

        return $command;
    }
}