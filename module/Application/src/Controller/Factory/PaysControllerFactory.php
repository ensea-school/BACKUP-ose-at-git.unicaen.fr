<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Controller\Factory;

use Application\Controller\PaysController;
use Psr\Container\ContainerInterface;

class PaysControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return PaysController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $renderer   = $container->get('ViewRenderer');
        $controller = new PaysController($renderer);

        return $controller;
    }
}