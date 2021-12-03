<?php

namespace Plafond\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of DerogationControllerFactory
 *
 * @author UnicaenCode
 */
class DerogationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DerogationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DerogationController
    {
        $controller = new DerogationController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}