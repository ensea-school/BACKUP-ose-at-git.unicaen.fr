<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/
namespace OffreFormation\Controller\Factory;

use OffreFormation\Controller\OffreFormationController;
use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

class OffreFormationControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return OffreFormationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $renderer   = $container->get('ViewRenderer');
        $controller = new OffreFormationController($renderer);
        $controller->setServiceSchema($container->get(SchemaService::class));

        return $controller;
    }
}