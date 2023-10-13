<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/
namespace OffreFormation\Controller\Factory;

use Lieu\Form\Element\Structure;
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
        $structureElement = $container->get('FormElementManager')->get(Structure::class);

        $controller = new OffreFormationController($structureElement);
        $controller->setServiceSchema($container->get(SchemaService::class));

        return $controller;
    }
}