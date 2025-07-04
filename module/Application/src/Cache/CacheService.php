<?php

namespace Application\Cache;

class CacheService
{
    private $cacheDir;



    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }



    public function exists($class, string $key): bool
    {
        return file_exists($this->keyToFile($class, $key));
    }



    public function remove($class, string $key): CacheService
    {
        $file = $this->keyToFile($class, $key);
        if (file_exists($file)) {
            unlink($file);
        }

        return $this;
    }



    public function get($class, string $key)
    {
        $content = (include $this->keyToFile($class, $key));

        return $content;
    }



    public function set($class, $key, $value): CacheService
    {
        $content = "<?php\n\nRETURN " . var_export($value, true) . ';';

        $filename = $this->keyToFile($class, $key);
        if (!is_dir(dirname($filename))) {
            mkdir(dirname($filename), 0777, true);
        }
        file_put_contents($filename, $content);
        @chmod($filename, 0666);

        return $this;
    }



    public function createContainer($class)
    {
        $container = new CacheContainer($this, $class);

        return $container;
    }



    private function keyToFile($class, string $key)
    {
        if (is_object($class)) $class = get_class($class);

        return $this->cacheDir . str_replace('\\', '/', $class) . '/' . $key;
    }
}