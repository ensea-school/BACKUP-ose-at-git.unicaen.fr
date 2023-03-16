<?php

namespace Agrement\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


class TblAgrementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TblAgrementService
     *
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TblAgrementService
    {
        $service = new TblAgrementService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}