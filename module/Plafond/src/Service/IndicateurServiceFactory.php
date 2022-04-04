<?php

namespace Plafond\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of IndicateurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class IndicateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return IndicateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): IndicateurService
    {
        $service = new IndicateurService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}