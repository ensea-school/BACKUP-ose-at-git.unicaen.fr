<?php

namespace Lieu\View\Helper;

use Psr\Container\ContainerInterface;

class EtablissementViewHelperFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): EtablissementViewHelper
    {
        $viewHelper = new EtablissementViewHelper();

        return $viewHelper;
    }
}