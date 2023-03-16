<?php

namespace Mission\Controller;

use Psr\Container\ContainerInterface;


/**
 * Description of OffreEmploiControllerFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return OffreEmploiController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): OffreEmploiController
    {
        $controller = new OffreEmploiController();

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}