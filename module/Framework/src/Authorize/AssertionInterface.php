<?php

namespace Framework\Authorize;

interface AssertionInterface
{
    public function assert(array $context): bool;
}
