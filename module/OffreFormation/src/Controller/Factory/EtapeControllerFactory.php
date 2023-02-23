<?php

namespace OffreFormation\Controller\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Controller\EtapeController;


/**
 * Description of EtapeControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeController
    {
        $controller = new EtapeController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}

