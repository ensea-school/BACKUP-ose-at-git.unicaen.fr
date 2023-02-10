<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\EtapeService;


/**
 * Description of EtapeServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class EtapeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EtapeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EtapeService
    {
        $service = new EtapeService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}