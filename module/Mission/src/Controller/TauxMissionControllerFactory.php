<?php

namespace Mission\Controller;

use Psr\Container\ContainerInterface;

/**
 * Description of TauxMissionControllerFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class TauxMissionControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxMissionController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxMissionController
    {
        $controller = new TauxMissionController;
            /* Injectez vos dépendances ICI */
            return $controller;
        }
}

