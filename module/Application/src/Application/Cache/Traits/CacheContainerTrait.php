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

    /**
     *
     * @return CacheContainer
     */
    public function getCacheContainer($class=null)
    {
        /** @var CacheService $cacheService */
        $cacheService = \Application::$container->get(CacheService::class);

        if (!$class) $class = $this;
        $container = new CacheContainer($cacheService, $class);

        return $container;
    }

}