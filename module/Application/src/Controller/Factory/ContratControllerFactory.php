<?php

namespace Application\Controller\Factory;

use Application\Controller\ContratController;
use Psr\Container\ContainerInterface;

class ContratControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return ContratController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {

        $renderer = $container->get('ViewRenderer');

        $controller = new ContratController($renderer);

        return $controller;
    }

}