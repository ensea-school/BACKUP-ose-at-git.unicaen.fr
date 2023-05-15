<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of ModulateurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ModulateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ModulateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ModulateurService
    {
        $service = new ModulateurService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}