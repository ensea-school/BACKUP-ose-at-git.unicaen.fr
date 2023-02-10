<?php

namespace OffreFormation\Controller\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Controller\EtapeCentreCoutController;


/**
 * Description of EtapeCentreCoutControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeCentreCoutControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeCentreCoutController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeCentreCoutController
    {
        $controller = new EtapeCentreCoutController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}

