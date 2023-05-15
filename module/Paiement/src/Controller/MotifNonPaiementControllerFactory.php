<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of MotifNonPaiementControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MotifNonPaiementControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MotifNonPaiementController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MotifNonPaiementController
    {
        $controller = new MotifNonPaiementController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}