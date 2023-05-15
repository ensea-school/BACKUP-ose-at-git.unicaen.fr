<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeModulateurStructureServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeModulateurStructureServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeModulateurStructureService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeModulateurStructureService
    {
        $service = new TypeModulateurStructureService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}