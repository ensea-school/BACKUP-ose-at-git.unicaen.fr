<?php

namespace Intervenant\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of StatutControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class StatutControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return StatutController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): StatutController
    {
        $controller = new StatutController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}