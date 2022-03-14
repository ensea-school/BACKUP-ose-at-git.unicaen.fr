<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Controller\Factory;

use Application\Controller\TypeFormationController;
use Psr\Container\ContainerInterface;

class TypeFormationControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return TypeFormationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $renderer   = $container->get('ViewRenderer');
        $controller = new TypeFormationController($renderer);

        return $controller;
    }
}