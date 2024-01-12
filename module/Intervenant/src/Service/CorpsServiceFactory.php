<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of CorpsServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class CorpsServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CorpsService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new CorpsService();

        return $service;
    }
}