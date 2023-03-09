<?php

namespace Contrat\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of TypeContratServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeContratServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeContratService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeContratService
    {
        $service = new TypeContratService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

