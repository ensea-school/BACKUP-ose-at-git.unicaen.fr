<?php

namespace Workflow\Service;

use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use Unicaen\Framework\Application\Application;

class EtatLieuxIntervenantServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): EtatLieuxIntervenantService
    {
        return new EtatLieuxIntervenantService(
            $container->get('ViewHelperManager')->getRenderer(),
            $container->get(Bdd::class),
            trim(Application::getInstance()->version()),
        );
    }
}
