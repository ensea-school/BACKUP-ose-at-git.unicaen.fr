<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Controller\Factory;

use Application\Controller\DepartementController;
use Psr\Container\ContainerInterface;

class DepartementControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return DepartementController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $renderer   = $container->get('ViewRenderer');
        $controller = new DepartementController($renderer);

        return $controller;
    }
}