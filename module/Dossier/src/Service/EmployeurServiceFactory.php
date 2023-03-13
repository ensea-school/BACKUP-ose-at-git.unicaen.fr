<?php

namespace Dossier\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


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
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}