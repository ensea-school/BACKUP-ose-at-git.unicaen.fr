<?php

namespace Framework\Cache;

use Framework\Container\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemCache implements CacheInterface
{
    /**
     * @param array $config
     */
    public function __construct(
        #[Autowire(config: 'unicaen-framework/cache')]
        protected array $config
    )
    {
    }



    public function getConfig(): array
    {
        return $this->config;
    }



    public function setConfig(array $config): void
    {
        $this->config = $config;
    }



    public function has(string $key): bool
    {
        $filename = $this->keyToFilename($key);

        if (!is_file($filename)) {
            return false;
        }

        $resource = fopen($filename, 'r');
        $line     = fgets($resource);

        if ($line !== false) {
            $lifetime = (int)$line;
        } else {
            $lifetime = 0;
        }

        fclose($resource);

        return $lifetime === 0 || $lifetime > time();
    }



    public function get(string $key): mixed
    {
        $filename = $this->keyToFilename($key);

        if (!is_file($filename)) {
            return null;
        }

        $data     = '';
        $lifetime = -1;

        $resource = fopen($filename, 'r');
        $line     = fgets($resource);

        if ($line !== false) {
            $lifetime = (int)$line;
        }

        if ($lifetime !== 0 && $lifetime < time()) {
            fclose($resource);

            return null;
        }

        while (($line = fgets($resource)) !== false) {
            $data .= $line;
        }

        fclose($resource);

        return unserialize($data);
    }



    public function set(string $key, mixed $value, int $lifetime = 0): void
    {
        $filename = $this->keyToFilename($key);

        if ($lifetime > 0) {
            $lifetime = time() + $lifetime;
        }

        $this->writeFile($filename, $lifetime . PHP_EOL . serialize($value));
    }



    public function delete(string $key): void
    {
        $filename = $this->keyToFilename($key);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }



    public function clear(): void
    {
        $filesystem = new Filesystem();
        $cacheDir = $this->getCacheDir();

        if ($filesystem->exists($cacheDir)) {
            if (!str_ends_with($cacheDir, DIRECTORY_SEPARATOR)) {
                $cacheDir .= DIRECTORY_SEPARATOR;
            }

            $content = scandir($cacheDir);
            foreach( $content as $toRemove) {
                if ($toRemove != '.' && $toRemove != '..' ) {
                    $filesystem->remove($cacheDir . $toRemove);
                }
            }
        }
    }



    public function getCacheDir(): string
    {
        return $this->config['cache_dir'] ?? 'cache';
    }



    public function setCacheDir(string $cacheDir): void
    {
        $this->config['cache_dir'] = $cacheDir;
    }



    protected function keyToFilename(string $key): string
    {
        $path     = str_replace(['[', ']', '\\', '::'], ['_', '', DIRECTORY_SEPARATOR, '.'], $key);
        $cacheDir = $this->getCacheDir();
        if (!str_ends_with($cacheDir, DIRECTORY_SEPARATOR)) {
            $cacheDir .= DIRECTORY_SEPARATOR;
        }

        return $cacheDir . $path;
    }



    protected function writeFile(string $filename, string $content): bool
    {
        $filepath = pathinfo($filename, PATHINFO_DIRNAME);

        if (!$this->createPathIfNeeded($filepath)) {
            return false;
        }

        if (!is_writable($filepath)) {
            return false;
        }

        $tmpFile = tempnam($filepath, 'swap');
        @chmod($tmpFile, 0777);

        if (file_put_contents($tmpFile, $content) !== false) {
            @chmod($tmpFile, 0777);
            if (@rename($tmpFile, $filename)) {
                return true;
            }

            @unlink($tmpFile);
        }

        return false;
    }



    private function createPathIfNeeded(string $path): bool
    {
        if (!is_dir($path)) {
            if (@mkdir($path, 0777, true) === false && !is_dir($path)) {
                return false;
            }
        }

        return true;
    }
}