<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of ServiceAPayerServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ServiceAPayerServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ServiceAPayerService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ServiceAPayerService
    {
        $service = new ServiceAPayerService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}