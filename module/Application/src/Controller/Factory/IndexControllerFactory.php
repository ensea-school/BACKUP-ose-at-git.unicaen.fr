<?php

namespace Application\Controller\Factory;

use Application\Controller\IndexController;
use Psr\Container\ContainerInterface;

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
        return new IndexController();
    }

}