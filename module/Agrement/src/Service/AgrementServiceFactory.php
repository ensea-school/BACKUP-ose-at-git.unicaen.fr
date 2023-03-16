<?php

namespace Agrement\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


class AgrementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AgrementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AgrementService
    {
        $service = new AgrementService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}