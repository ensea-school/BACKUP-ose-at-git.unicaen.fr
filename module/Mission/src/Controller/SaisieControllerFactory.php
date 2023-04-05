<?php

namespace Mission\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of SaisieControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SaisieControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SaisieController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SaisieController
    {
        $controller = new SaisieController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}