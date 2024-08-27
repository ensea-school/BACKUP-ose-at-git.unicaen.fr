<?php

namespace Application\Service\Factory;

use Application\Service\WorkflowService;
use Psr\Container\ContainerInterface;
use Unicaen\Console\Console;
use UnicaenTbl\Service\TableauBordService;

class WorkflowServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new WorkflowService();

        $service->setServiceTableauBord(
            $container->get(TableauBordService::class)
        );

        if ($container->has('BjyAuthorize\Service\Authorize')) {
            $service->setServiceAuthorize(
                $container->get('BjyAuthorize\Service\Authorize')
            );
        }

        return $service;
    }

}