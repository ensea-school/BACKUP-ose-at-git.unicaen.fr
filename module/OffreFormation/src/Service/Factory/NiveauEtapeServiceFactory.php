<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\NiveauEtapeService;


/**
 * Description of NiveauEtapeServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class NiveauEtapeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return NiveauEtapeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): NiveauEtapeService
    {
        $service = new NiveauEtapeService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

