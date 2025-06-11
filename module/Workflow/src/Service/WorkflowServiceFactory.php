<?php

namespace Workflow\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;

class WorkflowServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $service = new WorkflowService();

        $service->setServiceTableauBord($container->get(TableauBordService::class));
        $service->setEntityManager($container->get(EntityManager::class));

        if ($container->has('BjyAuthorize\Service\Authorize')) {
            $service->setServiceAuthorize(
                $container->get('BjyAuthorize\Service\Authorize')
            );
        }

        return $service;
    }

}