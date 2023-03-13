<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\ElementModulateurService;


/**
 * Description of ElementModulateurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementModulateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementModulateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementModulateurService
    {
        $service = new ElementModulateurService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

