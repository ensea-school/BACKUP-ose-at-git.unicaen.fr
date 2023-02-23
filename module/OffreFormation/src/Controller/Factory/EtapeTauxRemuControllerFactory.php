<?php

namespace OffreFormation\Controller\Factory;

use OffreFormation\Controller\EtapeTauxRemuController;
use Psr\Container\ContainerInterface;


/**
 * Description of EtapeTauxRemuControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeTauxRemuControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeTauxRemuController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeTauxRemuController
    {
        $controller = new EtapeTauxRemuController();

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}

