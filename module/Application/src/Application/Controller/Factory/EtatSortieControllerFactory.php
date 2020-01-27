<?php

namespace Application\Controller\Factory;

use Application\Controller\EtatSortieController;
use Psr\Container\ContainerInterface;

class EtatSortieControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new EtatSortieController();

        return $controller;
    }

}