<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of IntervenantServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return IntervenantService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new IntervenantService;

        return $service;
    }
}