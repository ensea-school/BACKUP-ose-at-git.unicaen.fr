<?php

namespace Framework\Cache;

use Doctrine\Common\Cache\Cache;
use Framework\Application\Application;

class DoctrineFilesystemCacheAdapter implements Cache
{
    const DOCTRINE_KEY = 'Doctrine'.DIRECTORY_SEPARATOR;

    private CacheInterface $cache;

    public function __construct()
    {
        $container = Application::getInstance()->container();

        $this->cache = $container->get(FilesystemCache::class);
    }



    public function fetch($id): mixed
    {
        return $this->cache->get(self::DOCTRINE_KEY.$id);
    }



    public function contains($id): bool
    {
        return $this->cache->has(self::DOCTRINE_KEY.$id);
    }



    public function save($id, $data, $lifeTime = 0): bool
    {
        $this->cache->set(self::DOCTRINE_KEY.$id, $data, $lifeTime);

        return true;
    }



    public function delete($id): bool
    {
        $this->cache->delete(self::DOCTRINE_KEY.$id);

        return true;
    }



    public function getStats(): null
    {
        return null;
    }

}