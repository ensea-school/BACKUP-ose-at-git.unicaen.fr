<?php

namespace OffreFormation\Controller\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Controller\TypeInterventionController;


/**
 * Description of TypeInterventionControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionController
    {
        $controller = new TypeInterventionController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}

