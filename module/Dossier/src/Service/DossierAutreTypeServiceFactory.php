<?php

namespace Dossier\Service;

use Application\Constants;
use Doctrine\ORM\EntityManager;
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
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}