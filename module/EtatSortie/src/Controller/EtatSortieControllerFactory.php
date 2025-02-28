<?php

namespace EtatSortie\Controller;

use Psr\Container\ContainerInterface;

class EtatSortieControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new EtatSortieController();

        return $controller;
    }

}