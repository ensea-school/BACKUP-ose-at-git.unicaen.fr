<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of DotationServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DotationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DotationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DotationService
    {
        $service = new DotationService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}