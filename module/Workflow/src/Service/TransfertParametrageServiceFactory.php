<?php

namespace Workflow\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Unicaen\Framework\Application\Application;

class TransfertParametrageServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): TransfertParametrageService
    {
        $service = new TransfertParametrageService(
            trim(Application::getInstance()->version()),
        );
        $service->setEntityManager($container->get(EntityManager::class));

        return $service;
    }
}
