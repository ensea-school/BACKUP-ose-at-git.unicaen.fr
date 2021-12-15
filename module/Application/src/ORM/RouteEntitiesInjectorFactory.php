<?php

namespace Application\ORM;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of RouteEntitiesInjectorFactory
 *
 * @author UnicaenCode
 */
class RouteEntitiesInjectorFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RouteEntitiesInjector
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): RouteEntitiesInjector
    {
        $service = new RouteEntitiesInjector;
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}