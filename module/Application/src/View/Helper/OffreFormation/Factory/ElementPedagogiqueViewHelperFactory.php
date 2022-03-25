<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\View\Helper\OffreFormation\Factory;

use Application\View\Helper\OffreFormation\ElementPedagogiqueViewHelper;

use Psr\Container\ContainerInterface;
use UnicaenImport\Service\SchemaService;

class ElementPedagogiqueViewHelperFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return ElementPedagogiqueViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        $controller = new ElementPedagogiqueViewHelper();
        $controller->setServiceSchema($container->get(SchemaService::class));

        return $controller;
    }
}