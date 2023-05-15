<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeRessourceServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeRessourceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeRessourceService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeRessourceService
    {
        $service = new TypeRessourceService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}