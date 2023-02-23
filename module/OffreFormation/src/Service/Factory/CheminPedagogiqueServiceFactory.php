<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\CheminPedagogiqueService;



/**
 * Description of CheminPedagogiqueServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CheminPedagogiqueServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CheminPedagogiqueService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CheminPedagogiqueService
    {
        $service = new CheminPedagogiqueService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}