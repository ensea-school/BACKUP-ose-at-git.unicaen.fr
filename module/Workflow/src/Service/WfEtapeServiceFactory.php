<?php

namespace Workflow\Service;

use Psr\Container\ContainerInterface;

class WfEtapeServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new WfEtapeService();

        return $service;
    }

}