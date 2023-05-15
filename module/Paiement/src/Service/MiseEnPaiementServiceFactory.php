<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of MiseEnPaiementServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MiseEnPaiementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MiseEnPaiementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MiseEnPaiementService
    {
        $service = new MiseEnPaiementService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}