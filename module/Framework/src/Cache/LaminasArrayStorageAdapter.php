<?php

namespace Framework\Cache;

use Laminas\Cache\Storage\StorageInterface;
use Laminas\Serializer\Adapter\AdapterOptions;
use Psr\Container\ContainerInterface;

class LaminasArrayStorageAdapter implements StorageInterface
{
    private CacheInterface $cache;



    public function __construct()
    {
        $this->cache = new ArrayCache();
    }



    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new Self();
    }



    public function setOptions($options): void
    {
        return;
    }



    public function getOptions(): AdapterOptions
    {
        return new AdapterOptions([]);
    }



    public function getItem($key, &$success = null, mixed &$casToken = null): mixed
    {
        return $this->cache->get($key);
    }



    public function getItems(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = $this->cache->get($key);
        }
        return $items;
    }



    public function hasItem($key): bool
    {
        return $this->cache->has($key);
    }



    public function hasItems(array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            if ($this->cache->has($key)) {
                $items[] = $key;
            }
        }
        return $items;
    }



    public function getMetadata($key): array
    {
        return [];
    }



    public function getMetadatas(array $keys): array
    {
        return [];
    }



    public function setItem($key, mixed $value): bool
    {
        $this->cache->set($key, $value);

        return true;
    }



    public function setItems(array $keyValuePairs): array
    {
        foreach ($keyValuePairs as $key => $value) {
            $this->cache->set($key, $value);
        }

        return [];
    }



    public function addItem($key, mixed $value): bool
    {
        $this->cache->set($key, $value);

        return true;
    }



    public function addItems(array $keyValuePairs): array
    {
        foreach ($keyValuePairs as $key => $value) {
            $this->cache->set($key, $value);
        }
        return [];
    }



    public function replaceItem($key, mixed $value): bool
    {
        $this->cache->set($key, $value);
        return true;
    }



    public function replaceItems(array $keyValuePairs): array
    {
        foreach ($keyValuePairs as $key => $value) {
            $this->cache->set($key, $value);
        }
        return [];
    }



    public function checkAndSetItem(mixed $token, $key, mixed $value): bool
    {
        $this->cache->set($key, $value);
        return true;
    }



    public function touchItem($key): bool
    {
        $this->cache->delete($key);
        return true;
    }



    public function touchItems(array $keys): array
    {
        foreach ($keys as $key) {
            $this->cache->delete($key);
        }
        return [];
    }



    public function removeItem($key): bool
    {
        $this->cache->delete($key);

        return true;
    }



    public function removeItems(array $keys): array
    {
        foreach ($keys as $key) {
            $this->cache->delete($key);
        }
        return [];
    }



    public function incrementItem($key, $value): bool
    {
        $this->cache->set($key, $value);
        return true;
    }



    public function incrementItems(array $keyValuePairs): array
    {
        foreach ($keyValuePairs as $key => $value) {
            $this->cache->set($key, $value);
        }
        return $keyValuePairs;
    }



    public function decrementItem($key, $value): bool
    {
        $this->cache->set($key, $value);

        return true;
    }



    public function decrementItems(array $keyValuePairs)
    {
        foreach ($keyValuePairs as $key => $value) {
            $this->cache->set($key, $value);
        }
        return $keyValuePairs;
    }



    public function getCapabilities(): mixed
    {
        return null;
    }


}