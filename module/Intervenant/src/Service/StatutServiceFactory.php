<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of StatutServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class StatutServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return StatutService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new StatutService();

        return $service;
    }
}