<?php

namespace ExportRh\Controller;

use Psr\Container\ContainerInterface;


/**
 * Description of AdministrationControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
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
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new AdministrationController();

        return $controller;
    }
}