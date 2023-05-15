<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeModulateurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeModulateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeModulateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeModulateurService
    {
        $service = new TypeModulateurService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}