<?php

namespace Utilisateur\Service;

use Psr\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;


/**
 * Description of UtilisateurServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class UtilisateurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UtilisateurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new UtilisateurService();
        $service->setUserService($container->get(UserService::class));

        return $service;
    }
}