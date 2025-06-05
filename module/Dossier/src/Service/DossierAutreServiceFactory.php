<?php

namespace Dossier\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;


class DossierAutreServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DossierAutreService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DossierAutreService
    {
        $service = new DossierAutreService();
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}