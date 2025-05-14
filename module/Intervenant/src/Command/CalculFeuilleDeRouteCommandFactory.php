<?php

namespace Intervenant\Command;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;


/**
 * Description of ExempleCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculFeuilleDeRouteCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CalculFeuilleDeRouteCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CalculFeuilleDeRouteCommand
    {
        $command = new CalculFeuilleDeRouteCommand;
        $command->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $command;
    }
}