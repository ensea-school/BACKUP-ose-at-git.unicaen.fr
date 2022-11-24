<?php

namespace Application\Controller\Factory;

use Application\Controller\EmployeurController;
use Psr\Container\ContainerInterface;

class EmployeurControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param                    $requestedName
     * @param null $options
     *
     * @return EmployeurController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {

        $controller = new EmployeurController();

        return $controller;
    }
}