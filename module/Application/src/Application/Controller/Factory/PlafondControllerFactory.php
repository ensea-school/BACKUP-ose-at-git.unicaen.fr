<?php

namespace Application\Controller\Factory;

use Application\Controller\PlafondController;
use Interop\Container\ContainerInterface;


/**
 * Description of PlafondControllerFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PlafondController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new PlafondController;

        return $controller;
    }
}