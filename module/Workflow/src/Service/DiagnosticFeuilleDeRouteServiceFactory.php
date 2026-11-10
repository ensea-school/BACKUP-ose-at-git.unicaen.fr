<?php

namespace Workflow\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use Unicaen\Framework\Router\Router;
use UnicaenTbl\Service\TableauBordService;

class DiagnosticFeuilleDeRouteServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DiagnosticFeuilleDeRouteService
    {
        $service = new DiagnosticFeuilleDeRouteService(
            $container->get(TableauBordService::class),
            $container->get(Bdd::class),
            $container->get(Router::class),
        );
        $service->setEntityManager($container->get(EntityManager::class));

        return $service;
    }
}
