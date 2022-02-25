<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeIntervenantServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeIntervenantServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeIntervenantService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeIntervenantService
    {
        $service = new TypeIntervenantService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}