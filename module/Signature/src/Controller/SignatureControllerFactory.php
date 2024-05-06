<?php

namespace Signature\Controller;


use Psr\Container\ContainerInterface;
use UnicaenSignature\Service\SignatureService;

/**
 * Description of SignatureControllerFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SignatureControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SandboxController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SignatureController
    {
        $controller       = new SignatureController();
        $serviceSignature = new SignatureService();
        $controller->setServiceSignature($serviceSignature);

        /* Injectez vos dépendances ICI */

        return $controller;
    }
}

