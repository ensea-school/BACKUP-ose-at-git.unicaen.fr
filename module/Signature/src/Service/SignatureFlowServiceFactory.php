<?php

namespace Signature\Service;

use Doctrine\ORM\EntityManager;
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
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}