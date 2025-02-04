<?php

namespace Application\Service\Factory;

use Application\Service\AffectationService;
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
        $doctrineCache = $container->get('doctrine.cache.filesystem');
        $service = new AffectationService;
        $service->setDoctrineCache($doctrineCache);

        return $service;
    }
}