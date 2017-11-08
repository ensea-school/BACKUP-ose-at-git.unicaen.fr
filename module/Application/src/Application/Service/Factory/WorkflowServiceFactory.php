<?php

namespace Application\Service\Factory;

use Application\Service\ContextService;
use Application\Service\WorkflowService;
use UnicaenTbl\Service\TableauBordService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WorkflowServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new WorkflowService();

        $service->setServiceTableauBord(
            $serviceLocator->get(TableauBordService::class)
        );

        $service->setServiceContext(
            $serviceLocator->get(ContextService::class)
        );

        $service->setServiceAuthorize(
            $serviceLocator->get('BjyAuthorize\Service\Authorize')
        );

        return $service;
    }
}