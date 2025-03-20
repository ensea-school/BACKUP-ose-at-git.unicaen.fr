<?php

namespace Service\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeServiceServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeServiceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeServiceService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeServiceService
    {
        $service = new TypeServiceService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}