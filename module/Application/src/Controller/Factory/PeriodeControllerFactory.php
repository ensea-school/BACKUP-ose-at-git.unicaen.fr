<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Controller\Factory;

use Application\Controller\PeriodeController;
use Psr\Container\ContainerInterface;

class PeriodeControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return PeriodeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $renderer   = $container->get('ViewRenderer');
        $controller = new PeriodeController($renderer);

        return $controller;
    }
}