<?php

namespace Chargens\View\Helper;

use Psr\Container\ContainerInterface;

class ChargensViewHelperFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): ChargensViewHelper
    {
        $helper = new ChargensViewHelper();

        return $helper;
    }
}