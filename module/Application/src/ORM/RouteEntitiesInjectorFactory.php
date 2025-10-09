<?php

namespace Application\ORM;

use Doctrine\ORM\EntityManager;
use Unicaen\Framework\User\UserManager;
use Psr\Container\ContainerInterface;


/**
 * Description of RouteEntitiesInjectorFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
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
        $service = new RouteEntitiesInjector(
            $container->get(UserManager::class),
        );
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}