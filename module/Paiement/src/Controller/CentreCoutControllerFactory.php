<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of CentreCoutControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CentreCoutControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CentreCoutController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CentreCoutController
    {
        $controller = new CentreCoutController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}