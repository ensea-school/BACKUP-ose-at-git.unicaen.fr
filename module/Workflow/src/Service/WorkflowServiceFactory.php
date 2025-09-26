<?php

namespace Workflow\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use UnicaenTbl\Service\TableauBordService;

class WorkflowServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $urlManager = $container->get('ViewHelperManager')->get('url');

        $service = new WorkflowService($urlManager);

        $service->setServiceTableauBord($container->get(TableauBordService::class));
        $service->setEntityManager($container->get(EntityManager::class));
        $service->setBdd($container->get(Bdd::class));

        return $service;
    }

}