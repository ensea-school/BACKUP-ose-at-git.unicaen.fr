<?php

namespace Signature\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

/**
 * Description of SignatureFlowStepServiceFactory
 *
 */
class SignatureFlowStepServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return SignatureFlowService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): SignatureFlowStepService
    {

        $service = new SignatureFlowStepService();
        $service->setEntityManager($container->get(EntityManager::class));


        /* Injectez vos dépendances ICI */

        return $service;
    }
}