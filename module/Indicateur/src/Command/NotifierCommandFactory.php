<?php

namespace Indicateur\Command;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;


/**
 * Description of NotifierCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class NotifierCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return NotifierCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): NotifierCommand
    {
        $command = new NotifierCommand;

        /* Injectez vos dépendances ICI */

        return $command;
    }
}