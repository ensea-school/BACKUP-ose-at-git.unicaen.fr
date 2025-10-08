<?php

namespace Framework\Controller\Plugin;

use Framework\Authorize\Authorize;
use Psr\Container\ContainerInterface;

class IsAllowedFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        return new IsAllowed(
            $container->get(Authorize::class)
        );
    }
}