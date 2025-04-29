<?php

namespace Workflow\Service;

use Psr\Container\ContainerInterface;

class WfEtapeDepServiceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new WfEtapeDepService();

        return $service;
    }

}