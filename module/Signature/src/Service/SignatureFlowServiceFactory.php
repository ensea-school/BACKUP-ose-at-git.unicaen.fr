<?php

namespace Signature\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;

/**
 * Description of SignatureFlowServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class SignatureFlowServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SignatureFlowService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SignatureFlowService
    {

        $service = new SignatureFlowService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}