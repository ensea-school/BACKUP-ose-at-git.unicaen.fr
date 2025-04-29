<?php

namespace OffreFormation\Controller\Factory;

use OffreFormation\Controller\ElementPedagogiqueController;
use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use UnicaenImport\Service\SchemaService;


/**
 * Description of ElementPedagogiqueControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueControllerFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $renderer   = $container->get('ViewRenderer');
        $controller = new ElementPedagogiqueController($renderer);
        $controller->setServiceSchema($container->get(SchemaService::class));
        $controller->setBdd($container->get(Bdd::class));

        return $controller;
    }
}