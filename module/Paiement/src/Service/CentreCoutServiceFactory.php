<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of CentreCoutServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CentreCoutServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CentreCoutService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CentreCoutService
    {
        $service = new CentreCoutService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}