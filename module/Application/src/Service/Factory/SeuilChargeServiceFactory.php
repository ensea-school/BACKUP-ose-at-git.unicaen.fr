<?php

namespace Application\Service\Factory;

use Application\Service\SeuilChargeService;
use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;

class SeuilChargeServiceFactory
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