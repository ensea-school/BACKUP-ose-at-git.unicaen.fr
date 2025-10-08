<?php

namespace Framework\View\Helper;

use Framework\Authorize\Authorize;
use Psr\Container\ContainerInterface;

class IsAllowedFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): IsAllowed
    {
        return new IsAllowed(
            $container->get(Authorize::class),
        );
    }

}