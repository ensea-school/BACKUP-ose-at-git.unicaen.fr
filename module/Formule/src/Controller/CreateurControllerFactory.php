<?php

namespace Formule\Controller;

use Psr\Container\ContainerInterface;


/**
 * Description of CreateurControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CreateurControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CreateurController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CreateurController
    {
        $controller = new CreateurController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}