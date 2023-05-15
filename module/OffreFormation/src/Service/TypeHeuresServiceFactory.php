<?php

namespace OffreFormation\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeHeuresServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeHeuresServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeHeuresService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeHeuresService
    {
        $service = new TypeHeuresService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}