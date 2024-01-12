<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Intervenant\Controller;

use Psr\Container\ContainerInterface;

class GradeControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return GradeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new GradeController();

        return $controller;
    }
}