<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Interop\Container\ContainerInterface;
use UnicaenAuth\Service\UserContext;

class IndexControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new IndexController();

        /** @var UserContext $userContextService */
        $userContextService = $container->get(UserContext::class);

        $controller->setServiceUserContext($userContextService);

        return $controller;
    }

}