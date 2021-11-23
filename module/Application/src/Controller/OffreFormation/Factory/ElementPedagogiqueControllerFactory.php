<?php

namespace Application\Controller\OffreFormation\Factory;

use Application\Controller\OffreFormation\ElementPedagogiqueController;
use Psr\Container\ContainerInterface;


/**
 * Description of ElementPedagogiqueControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueControllerFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new ElementPedagogiqueController;

        return $controller;
    }
}