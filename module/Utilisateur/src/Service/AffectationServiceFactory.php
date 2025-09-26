<?php

namespace Utilisateur\Service;

use Psr\Container\ContainerInterface;

/**
 * Description of AffectationServiceFactory
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
class AffectationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AffectationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new AffectationService;

        return $service;
    }
}