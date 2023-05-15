<?php

namespace Paiement\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of TypeRessourceControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeRessourceControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeRessourceController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeRessourceController
    {
        $controller = new TypeRessourceController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}