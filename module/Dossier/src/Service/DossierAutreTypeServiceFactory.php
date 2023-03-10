<?php

namespace Dossier\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


class DossierAutreTypeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DossierAutreTypeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DossierAutreTypeService
    {
        $service = new DossierAutreTypeService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}