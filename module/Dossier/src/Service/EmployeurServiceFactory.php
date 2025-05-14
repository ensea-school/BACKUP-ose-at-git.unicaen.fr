<?php

namespace Dossier\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;


class EmployeurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return EmployeurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): EmployeurService
    {
        $service = new EmployeurService();
        $service->setEntityManager($container->get(EntityManager::class));
        $service->setBdd($container->get(Bdd::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}