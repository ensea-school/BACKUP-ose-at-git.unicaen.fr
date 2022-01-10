<?php

namespace ExportRh\Controller;

use Psr\Container\ContainerInterface;
use UnicaenSiham\Service\Siham;


/**
 * Description of AdministrationControllerFactory
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
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
        $siham = $container->get(Siham::class);

        $controller = new AdministrationController($siham);

        return $controller;
    }
}