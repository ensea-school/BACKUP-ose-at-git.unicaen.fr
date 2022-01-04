<?php

namespace Application\Service\Factory;

use Application\Service\WorkflowService;
use Psr\Container\ContainerInterface;
use UnicaenApp\Util;
use UnicaenTbl\Service\TableauBordService;

class WorkflowServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new WorkflowService();

        $service->setServiceTableauBord(
            $container->get(TableauBordService::class)
        );

        if (!Util::isConsole()) {
            $service->setServiceAuthorize(
                $container->get('BjyAuthorize\Service\Authorize')
            );
        }

        return $service;
    }

}