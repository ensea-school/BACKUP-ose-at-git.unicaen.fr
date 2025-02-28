<?php

namespace Chargens\Command;

use Psr\Container\ContainerInterface;


/**
 * Description of ChargensCalculEffectifCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ChargensCalculEffectifCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ChargensCalculEffectifCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ChargensCalculEffectifCommand
    {
        $command = new ChargensCalculEffectifCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}