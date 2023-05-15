<?php

namespace OffreFormation\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of CentreCoutEpServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CentreCoutEpServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CentreCoutEpService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CentreCoutEpService
    {
        $service = new CentreCoutEpService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}