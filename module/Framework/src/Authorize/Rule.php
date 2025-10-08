<?php

namespace Framework\Authorize;

use Closure;

class Rule
{
    /** @var array|string[] */
    public array $roles = [];

    /** @var array|string[] */
    public array $privileges = [];

    public null|string|Closure $assertion = null;

}