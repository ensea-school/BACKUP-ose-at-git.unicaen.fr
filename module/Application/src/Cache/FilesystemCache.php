<?php

namespace Application\Cache;

use Doctrine\Common\Cache\CacheProvider;
use function fclose;
use function fgets;
use function fopen;
use function is_file;
use function serialize;
use function time;
use function unserialize;

use const PHP_EOL;

class FilesystemCache extends CacheProvider
{
    public const EXTENSION = '.doctrinecache.data';

    /** @var int */
    private int $umask = 0000;

    private string $extension = self::EXTENSION;

    private string $cacheDir;

    /**
     * {@inheritdoc}
     */
    public function __construct($cacheDir = 'cache/Doctrine', $extension = self::EXTENSION, $umask = 0000)
    {
        $this->cacheDir = $cacheDir;
        $this->extension = $extension;
        $this->umask = $umask;
    }



    public function getFilename(string $id): string
    {
        return $this->cacheDir.'/'.base64_encode($id);
    }


    /**
     * {@inheritDoc}
     */
    protected function doDelete($id): bool
    {
        $filename = $this->getFilename($id);

        unset($filename);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    protected function doFlush()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    protected function doGetStats()
    {
        $storage = $this->storage;

        return [

        ];
    }


    /**
     * {@inheritdoc}
     */
    protected function doFetch($id)
    {
        $data     = '';
        $lifetime = -1;
        $filename = $this->getFilename($id);

        if (! is_file($filename)) {
            return false;
        }

        $resource = fopen($filename, 'r');
        $line     = fgets($resource);

        if ($line !== false) {
            $lifetime = (int) $line;
        }

        if ($lifetime !== 0 && $lifetime < time()) {
            fclose($resource);

            return false;
        }

        while (($line = fgets($resource)) !== false) {
            $data .= $line;
        }

        fclose($resource);

        return unserialize($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function doContains($id)
    {
        $lifetime = -1;
        $filename = $this->getFilename($id);

        if (! is_file($filename)) {
            return false;
        }

        $resource = fopen($filename, 'r');
        $line     = fgets($resource);

        if ($line !== false) {
            $lifetime = (int) $line;
        }

        fclose($resource);

        return $lifetime === 0 || $lifetime > time();
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        if ($lifeTime > 0) {
            $lifeTime = time() + $lifeTime;
        }

        $data     = serialize($data);
        $filename = $this->getFilename($id);

        return $this->writeFile($filename, $lifeTime . PHP_EOL . $data);
    }



    /**
     * Writes a string content to file in an atomic way.
     *
     * @param string $filename Path to the file where to write the data.
     * @param string $content  The content to write
     *
     * @return bool TRUE on success, FALSE if path cannot be created, if path is not writable or an any other error.
     */
    protected function writeFile(string $filename, string $content): bool
    {
        $filepath = pathinfo($filename, PATHINFO_DIRNAME);

        if (! $this->createPathIfNeeded($filepath)) {
            return false;
        }

        if (! is_writable($filepath)) {
            return false;
        }

        $tmpFile = tempnam($filepath, 'swap');
        @chmod($tmpFile, 0666 & (~$this->umask));

        if (file_put_contents($tmpFile, $content) !== false) {
            @chmod($tmpFile, 0666 & (~$this->umask));
            if (@rename($tmpFile, $filename)) {
                return true;
            }

            @unlink($tmpFile);
        }

        return false;
    }



    /**
     * Create path if needed.
     *
     * @return bool TRUE on success or if path already exists, FALSE if path cannot be created.
     */
    private function createPathIfNeeded(string $path): bool
    {
        if (! is_dir($path)) {
            if (@mkdir($path, 0777 & (~$this->umask), true) === false && ! is_dir($path)) {
                return false;
            }
        }

        return true;
    }
}
