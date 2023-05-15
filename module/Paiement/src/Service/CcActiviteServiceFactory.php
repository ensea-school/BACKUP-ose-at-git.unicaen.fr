<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of CcActiviteServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CcActiviteServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CcActiviteService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CcActiviteService
    {
        $service = new CcActiviteService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}