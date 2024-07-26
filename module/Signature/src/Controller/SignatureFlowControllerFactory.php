<?php

namespace Signature\Controller;


use Psr\Container\ContainerInterface;
use Signature\Service\SignatureFlowService;
use Signature\Service\SignatureFlowStepService;
use UnicaenSignature\Service\ProcessService;
use UnicaenSignature\Service\SignatureConfigurationService;
use UnicaenSignature\Service\SignatureService;

/**
 * Description of SignatureFlowControllerFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SignatureFlowControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SandboxController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SignatureFlowController
    {
        $controller = new SignatureFlowController();
        $controller->setSignatureService($container->get(SignatureService::class));
        $controller->setSignatureConfigurationService($container->get(SignatureConfigurationService::class));
        $controller->setProcessService($container->get(ProcessService::class));
        $controller->setServiceSignatureFlow($container->get(SignatureFlowService::class));
        $controller->setserviceSignatureFlowStep($container->get(SignatureFlowStepService::class));

        return $controller;
    }
}

