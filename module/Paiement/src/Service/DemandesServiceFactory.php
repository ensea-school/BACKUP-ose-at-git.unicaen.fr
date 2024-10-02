<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of DemandesServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DemandesServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DemandesService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DemandesService
    {
        $service = new DemandesService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}