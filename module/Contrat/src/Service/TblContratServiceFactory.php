<?php

namespace Contrat\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TblContratServiceFactory
 *
 **/
class TblContratServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TblContratService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TblContratService
    {
        $service = new TblContratService();

        /* Injectez vos dépendances ICI */

        return $service;
    }
}