<?php

namespace Signature\Service;

use Application\Constants;
use Mission\Service\CandidatureService;
use Psr\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;
use UnicaenSignature\Service\SignatureService;


/**
 * Description of CircuitServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class CircuitServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CircuitService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CircuitService
    {

        $signatureService = $container->get(SignatureService::class);
        $service          = new CircuitService();
        /* Injectez vos dépendances ICI */
        $service->setSignatureService($signatureService);

        return $service;
    }
}