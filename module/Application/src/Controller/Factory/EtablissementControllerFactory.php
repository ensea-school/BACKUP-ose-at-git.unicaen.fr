<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Controller\Factory;

use Application\Controller\EtablissementController;
use Psr\Container\ContainerInterface;

class EtablissementControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return EtablissementController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $renderer   = $container->get('ViewRenderer');
        $controller = new EtablissementController($renderer);

        return $controller;
    }
}