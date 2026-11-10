<?php

namespace Workflow\Service;

use Psr\Container\ContainerInterface;
use Unicaen\Framework\Application\Application;

class RapportParametrageServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RapportParametrageService
    {
        return new RapportParametrageService(
            $container->get('ViewHelperManager')->getRenderer(),
            trim(Application::getInstance()->version()),
        );
    }
}
