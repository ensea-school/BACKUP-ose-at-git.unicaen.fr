<?php

namespace Mission\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use UnicaenMail\Service\Mail\MailService;


/**
 * Description of CandidatureServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class CandidatureServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CandidatureService
     */
    public function __invoke (ContainerInterface $container, $requestedName, $options = null): CandidatureService
    {

        $mailService = $container->get(MailService::class);
        $service     = new CandidatureService();
        $service->setMailService($mailService);
        $service->setEntityManager($container->get(EntityManager::class));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}