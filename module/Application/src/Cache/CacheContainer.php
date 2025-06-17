<?php

namespace Application\Cache;

class CacheContainer
{

    private CacheService $cacheService;

    private ?object $object = null;

    private string $class;

    private array $localCache = [];



    public function __construct(CacheService $cacheService, string|object $class)
    {
        $this->cacheService = $cacheService;
        if (is_object($class)) $this->object = $class;
        $this->class = is_object($class) ? get_class($class) : $class;
    }



    public function __get(string $name): mixed
    {
        if (!array_key_exists($name, $this->localCache)) {
            $this->localCache[$name] = $this->cacheService->get($this->class, $name);
        }

        return $this->localCache[$name];
    }



    public function __set(string $name, mixed $value): void
    {
        $this->localCache[$name] = $value;
        $this->cacheService->set($this->class, $name, $value);
    }



    public function __isset(string $name): bool
    {
        if (array_key_exists($name, $this->localCache)) {
            return true;
        }

        return $this->cacheService->exists($this->class, $name);
    }



    public function __unset(string $name): void
    {
        unset($this->localCache[$name]);
        $this->cacheService->remove($this->class, $name);
    }



    public function __call(string $name, array $arguments): mixed
    {
        if ($this->__isset($name)) {
            return $this->__get($name);
        } else {
            $callable = $arguments[0];
            if (is_string($callable) && $this->object && method_exists($this->object, $callable)) {
                $value = $this->object->$callable();
            } else {
                $value = $callable();
            }
            $this->__set($name, $value);

            return $value;
        }
    }
}