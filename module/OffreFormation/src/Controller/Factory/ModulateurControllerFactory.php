<?php

namespace OffreFormation\Controller\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Controller\ModulateurController;
use Unicaen\BddAdmin\Bdd;


/**
 * Description of ModulateurControllerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ModulateurControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ModulateurController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ModulateurController
    {
        $controller = new ModulateurController;

        $controller->setBdd($container->get(Bdd::class));

        return $controller;
    }
}

