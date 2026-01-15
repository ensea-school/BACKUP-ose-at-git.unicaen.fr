<?php

namespace Utilisateur\Controller;

use Laminas\Authentication\AuthenticationService;
use Unicaen\Framework\User\UserManager;
use Psr\Container\ContainerInterface;
use UnicaenAuthentification\Service\UserContext;
use Utilisateur\Connecteur\LdapConnecteur;
use Utilisateur\Provider\UserProvider;

class UtilisateurControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param                    $requestedName
     * @param null               $options
     *
     * @return UtilisateurController
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new UtilisateurController(
            $container->get(UserManager::class),
            $container->get(UserContext::class),
            $container->get(LdapConnecteur::class),
            $container->get(AuthenticationService::class),
            $container->get(UserProvider::class),
        );

        return $controller;
    }
}