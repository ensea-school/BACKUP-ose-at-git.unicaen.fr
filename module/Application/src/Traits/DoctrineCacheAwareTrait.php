<?php

namespace Application\Traits;

use Doctrine\Common\Cache\FilesystemCache;

trait DoctrineCacheAwareTrait
{
    /**
     * Retourne le cache de système de fichiers de Doctrine
     *
     * @return FilesystemCache
     */
    public function getCacheFilesystem(): FilesystemCache
    {
        return \Application::$container->get('doctrine.cache.filesystem');
    }
}