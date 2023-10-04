<?php

namespace Lieu\View\Helper;

use Psr\Container\ContainerInterface;

class StructureViewHelperFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): StructureViewHelper
    {
        $viewHelper = new StructureViewHelper();

        return $viewHelper;
    }
}