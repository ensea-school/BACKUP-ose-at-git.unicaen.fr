<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of CiviliteServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class CiviliteServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CiviliteService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new CiviliteService();

        return $service;
    }
}