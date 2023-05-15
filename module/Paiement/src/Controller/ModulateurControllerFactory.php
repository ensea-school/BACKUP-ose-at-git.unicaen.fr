<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of ModulateurControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ModulateurControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ModulateurController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ModulateurController
    {
        $controller = new ModulateurController();

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}