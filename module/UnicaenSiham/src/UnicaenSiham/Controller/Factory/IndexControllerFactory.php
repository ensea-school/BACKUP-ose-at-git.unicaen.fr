<?php

namespace UnicaenSiham\Controller\Factory;


use Psr\Container\ContainerInterface;
use UnicaenSiham\Controller\IndexController;
use UnicaenSiham\Service\Siham;

class IndexControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        $siham = $container->get(Siham::class);

        $controller = new IndexController($siham);

        return $controller;
    }
}