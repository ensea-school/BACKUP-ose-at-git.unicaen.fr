<?php

namespace Framework\Authorize;

class Authorize {

    public function __construct(
        private readonly \BjyAuthorize\Service\Authorize $oldAuthorize
    )
    {

    }



    public function isAllowedResource(string $resource): bool
    {
        return $this->oldAuthorize->isAllowed($resource);
    }
}