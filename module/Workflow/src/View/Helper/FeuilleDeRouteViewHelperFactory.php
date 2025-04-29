<?php

namespace Workflow\View\Helper;

use Psr\Container\ContainerInterface;


class FeuilleDeRouteViewHelperFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): FeuilleDeRouteViewHelper
    {
        $viewHelper = new FeuilleDeRouteViewHelper();

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}