<?php

namespace Application\Service\Factory;

use Application\Service\WorkflowService;
use Interop\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;
use Zend\Console\Console;
use Zend\ServiceManager\Factory\FactoryInterface;

class WorkflowServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new WorkflowService();

        $service->setServiceTableauBord(
            $container->get(TableauBordService::class)
        );

        if (!Console::isConsole()) {
            $service->setServiceAuthorize(
                $container->get('BjyAuthorize\Service\Authorize')
            );
        }

        return $service;
    }

}