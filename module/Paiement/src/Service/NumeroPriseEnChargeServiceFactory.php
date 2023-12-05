<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of NumeroPriseEnChargeServiceFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class NumeroPriseEnChargeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MotifNonPaiementService
     */
    public function __invoke (ContainerInterface $container, $requestedName, $options = null): NumeroPriseEnChargeService
    {
        $service = new NumeroPriseEnChargeService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}