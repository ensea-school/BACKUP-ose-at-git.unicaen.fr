<?php

namespace Mission\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of MissionControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionController
    {
        $controller = new MissionController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}