<?php

namespace Administration\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of GitRepoServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ParametresServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ParametresService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ParametresService
    {
        $service = new ParametresService();

        return $service;
    }
}