<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\ElementPedagogiqueService;


/**
 * Description of ElementPedagogiqueServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ElementPedagogiqueService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ElementPedagogiqueService
    {
        $service = new ElementPedagogiqueService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

