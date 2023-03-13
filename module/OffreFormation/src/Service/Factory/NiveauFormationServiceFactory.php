<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\NiveauFormationService;


/**
 * Description of NiveauFormationServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class NiveauFormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return NiveauFormationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): NiveauFormationService
    {
        $service = new NiveauFormationService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

