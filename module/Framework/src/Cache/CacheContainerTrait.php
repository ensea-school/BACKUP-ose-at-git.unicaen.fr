<?php

namespace Framework\Cache;

use Framework\Application\Application;

trait CacheContainerTrait
{
    private ?CacheContainer $__cacheContainer = null;

    public function getCacheContainer(?string $class = null, string $cacheServiceClass = ArrayCache::class): CacheContainer
    {
        if (!isset($this->__cacheContainer)) {
            $container = Application::getInstance()->container();

            /**
             * @var $cacheService CacheInterface
             */
            $cacheService = $container->get($cacheServiceClass);

            if (!$cacheService instanceof CacheInterface) {
                throw new \EXception( 'Cache must be a '.CacheInterface::class .' implementation');
            }

            if (!$class) $class = $this;
            $this->__cacheContainer = new CacheContainer($cacheService, $class);
        }

        return $this->__cacheContainer;
    }

}