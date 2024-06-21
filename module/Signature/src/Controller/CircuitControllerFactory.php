<?php

namespace Signature\Controller;


use Psr\Container\ContainerInterface;
use Signature\Service\CircuitService;
use UnicaenSignature\Service\SignatureConfigurationService;
use UnicaenSignature\Service\SignatureService;

/**
 * Description of CircuitControllerFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class CircuitControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SandboxController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CircuitController
    {
        $controller = new CircuitController();
        $controller->setServiceCircuit($container->get(CircuitService::class));

        return $controller;
    }
}

