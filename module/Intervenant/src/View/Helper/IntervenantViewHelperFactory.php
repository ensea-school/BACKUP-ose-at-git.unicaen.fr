<?php

namespace Intervenant\View\Helper;

use Psr\Container\ContainerInterface;


class IntervenantViewHelperFactory
{

    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): IntervenantViewHelper
    {
        $viewHelper = new IntervenantViewHelper;

        /* Injectez vos dépendances ICI */

        return $viewHelper;
    }
}