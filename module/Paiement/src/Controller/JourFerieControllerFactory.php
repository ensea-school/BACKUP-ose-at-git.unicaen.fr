<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of JourFerieControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class JourFerieControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return JourFerieController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): JourFerieController
    {
        $controller = new JourFerieController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}