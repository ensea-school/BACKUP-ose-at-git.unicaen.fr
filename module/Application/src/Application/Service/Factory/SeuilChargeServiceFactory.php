<?php

namespace Application\Service\Factory;

use Application\Service\SeuilChargeService;
use UnicaenTbl\Service\TableauBordService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SeuilChargeServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new SeuilChargeService();

        $service->setServiceTableauBord(
            $serviceLocator->get(TableauBordService::class)
        );

        return $service;
    }
}