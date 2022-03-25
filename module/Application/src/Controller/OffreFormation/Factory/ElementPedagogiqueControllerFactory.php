<?php

namespace Application\Controller\OffreFormation\Factory;

use Application\Controller\OffreFormation\ElementPedagogiqueController;
use Psr\Container\ContainerInterface;
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

        return $controller;
    }
}