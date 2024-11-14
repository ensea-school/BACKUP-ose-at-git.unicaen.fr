<?php

namespace Application\Controller\Factory;

use Application\Controller\AdministrationController;
use Application\Controller\ParametreController;
use Psr\Container\ContainerInterface;
use UnicaenSignature\Service\SignatureConfigurationService;

class ParametereControllerFactory
{
    /**
     * Create controller
     *
     * @param ContainerInterface $container
     *
     * @return ParametreController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new ParametreController();
        $controller->setSignatureConfigurationService(SignatureConfigurationService::class);

        return $controller;
    }

}