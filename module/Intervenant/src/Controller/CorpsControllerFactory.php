<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Intervenant\Controller;

use Psr\Container\ContainerInterface;

class CorpsControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return CorpsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new CorpsController();

        return $controller;
    }
}