<?php

namespace Framework\Cache;


interface CacheInterface
{

    public function getConfig(): array;



    public function setConfig(array $config): void;



    public function has(string $key): bool;



    public function get(string $key): mixed;



    public function set(string $key, mixed $value, int $lifetime = 0);



    public function delete(string $key): void;



    public function clear(): void;
}