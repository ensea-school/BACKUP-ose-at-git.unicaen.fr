<?php

namespace Framework\View\Helper;

use Framework\Authorize\Authorize;
use Laminas\View\Helper\AbstractHelper;

class IsAllowed extends AbstractHelper
{
    public function __construct(
        private readonly Authorize $authorize
    )
    {
    }



    public function __invoke(mixed $resource, ?string $privilege = null): bool
    {
        return $this->authorize->isAllowed($resource, $privilege);
    }
}
