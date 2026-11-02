<?php

namespace Application\HostLocalization;

use Psr\Container\ContainerInterface;

class HostLocalizationOseFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $hl = new HostLocalizationOse();

        return $hl;
    }
}