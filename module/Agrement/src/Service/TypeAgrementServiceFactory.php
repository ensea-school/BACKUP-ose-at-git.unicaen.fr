<?php

namespace Agrement\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


class TypeAgrementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeAgrementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeAgrementService
    {
        $service = new TypeAgrementService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}