<?php

namespace Service\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of RegleStructureValidationControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class RegleStructureValidationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RegleStructureValidationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): RegleStructureValidationController
    {
        $controller = new RegleStructureValidationController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}