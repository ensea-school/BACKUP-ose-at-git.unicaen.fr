<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of DemandesControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DemandesControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DemandesController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DemandesController
    {
        $controller = new DemandesController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}