<?php

namespace Application\Cache\Factory;

use Application\Cache\CacheService;
use Psr\Container\ContainerInterface;


/**
 * Description of CacheServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class CacheServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CacheService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $cacheDir  = 'cache/';

        if (!is_dir($cacheDir)) {
            throw new \Exception('Le dossier de cache de OSE est mal renseigné ou inexistant');
        }

        $service = new CacheService($cacheDir);

        return $service;
    }
}