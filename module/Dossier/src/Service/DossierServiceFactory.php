<?php

namespace Dossier\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


class DossierServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DossierService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DossierService
    {
        $service = new DossierService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}