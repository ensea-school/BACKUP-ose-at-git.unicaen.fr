<?php

namespace Plafond\Controller;

use Interop\Container\ContainerInterface;


/**
 * Description of PlafondStructureControllerFactory
 *
 * @author UnicaenCode
 */
class PlafondStructureControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondStructureController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): PlafondStructureController
    {
        $controller = new PlafondStructureController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}