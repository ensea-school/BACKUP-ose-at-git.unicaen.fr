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
    public function getCacheContainer($class = null)
    {
        if (!isset($this->__cacheContainer)) {
            /** @var CacheService $cacheService */
            $cacheService = \OseAdmin::instance()->container()->get(CacheService::class);

            if (!$class) $class = $this;
            $this->__cacheContainer = new CacheContainer($cacheService, $class);
        }

        return $this->__cacheContainer;
    }

}