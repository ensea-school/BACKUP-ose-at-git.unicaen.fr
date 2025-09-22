<?php

namespace Application\Cache\Traits;

use Application\Cache\CacheContainer;
use Application\Cache\CacheService;

/**
 * Description of CacheContainerTrait
 *
 */
trait CacheContainerTrait
{
    private $__cacheContainer;

    /**
     *
     * @return CacheContainer
     */
    public function getCacheContainer(?string $class = null)
    {
        if (!isset($this->__cacheContainer)) {
            /** @var CacheService $cacheService */
            $cacheService = \Framework\Application\Application::getInstance()->container()->get(CacheService::class);

            if (!$class) $class = $this;
            $this->__cacheContainer = new CacheContainer($cacheService, $class);
        }

        return $this->__cacheContainer;
    }

}