<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of DomaineFonctionnelServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DomaineFonctionnelServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DomaineFonctionnelService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DomaineFonctionnelService
    {
        $service = new DomaineFonctionnelService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

