<?php

namespace Workflow\Controller;

use Psr\Container\ContainerInterface;



/**
 * Description of AdministrationControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AdministrationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AdministrationController
    {
        $controller = new AdministrationController;

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}