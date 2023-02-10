<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\OffreFormationService;



/**
 * Description of OffreFormationServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class OffreFormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return OffreFormationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): OffreFormationService
    {
        $service = new OffreFormationService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

