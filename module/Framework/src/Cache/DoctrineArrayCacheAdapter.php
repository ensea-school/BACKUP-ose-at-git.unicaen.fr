<?php

namespace Framework\Cache;

use Doctrine\Common\Cache\Cache;

class DoctrineArrayCacheAdapter implements Cache
{
    private CacheInterface $cache;

    public function __construct()
    {
        $this->cache = new ArrayCache();
    }



    public function fetch($id): mixed
    {
        return $this->cache->get($id);
    }



    public function contains($id): bool
    {
        return $this->cache->has($id);
    }



    public function save($id, $data, $lifeTime = 0): bool
    {
        $this->cache->set($id, $data, $lifeTime);

        return true;
    }



    public function delete($id): bool
    {
        $this->cache->delete($id);

        return true;
    }



    public function getStats(): null
    {
        return null;
    }

}