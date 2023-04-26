<?php

namespace Contrat\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of ContratServiceListeServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ContratServiceListeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContratServiceListeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ContratServiceListeService
    {
        $service = new ContratServiceListeService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

