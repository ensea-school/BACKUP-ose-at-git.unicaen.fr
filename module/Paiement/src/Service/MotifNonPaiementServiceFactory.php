<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of MotifNonPaiementServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MotifNonPaiementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MotifNonPaiementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MotifNonPaiementService
    {
        $service = new MotifNonPaiementService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}