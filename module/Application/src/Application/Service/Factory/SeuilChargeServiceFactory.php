<?php

namespace Application\Service\Factory;

use Application\Service\SeuilChargeService;
use Interop\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;
use Zend\ServiceManager\Factory\FactoryInterface;

class SeuilChargeServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new SeuilChargeService();

        $service->setServiceTableauBord(
            $container->get(TableauBordService::class)
        );

        return $service;
    }
}