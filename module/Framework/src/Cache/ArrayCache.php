<?php

namespace Framework\Cache;

class ArrayCache implements CacheInterface
{
    protected array $data = [];



    public function __construct()
    {
    }



    public function getConfig(): array
    {
        return [];
    }



    public function setConfig(array $config): void
    {
        // pas de config pour le arrayCache
        return;
    }



    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }



    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }



    public function set(string $key, mixed $value, int $lifetime = 0)
    {
        $this->data[$key] = $value;
    }



    public function delete(string $key): void
    {
        unset($this->data[$key]);
    }



    public function clear(): void
    {
        $this->data = [];
    }

}