<?php

namespace Contrat\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of ContratServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ContratServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ContratService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ContratService
    {
        $service = new ContratService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}
