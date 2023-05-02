<?php

namespace Mission\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of SuiviControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SuiviControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SuiviController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SuiviController
    {
        $controller = new SuiviController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}