<?php

namespace Framework\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Framework\Authorize\Authorize;

class IsAllowed extends AbstractPlugin
{

    public function __construct(
        private readonly Authorize $authorize)
    {
    }



    public function __invoke(mixed $resource, ?string $privilege = null): bool
    {
        return $this->authorize->isAllowed($resource, $privilege);
    }
}
