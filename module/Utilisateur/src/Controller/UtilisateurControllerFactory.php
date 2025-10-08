<?php

namespace Utilisateur\Controller;

use Framework\User\UserManager;
use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerInterface;
use UnicaenApp\Mapper\Ldap\People as LdapPeopleMapper;
use UnicaenAuthentification\Service\ShibService;
use UnicaenAuthentification\Service\UserContext;
use ZfcUser\Mapper\UserInterface;

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
        $userManager = $container->get(UserManager::class);

        /** @var UserInterface $mapper */
        $userMapper = $container->get('zfcuser_user_mapper');

        $authenticationService = $container->get(AuthenticationService::class);

        $options = $container->get('unicaen-auth_module_options');

        $shibService = $container->get(ShibService::class);

        $userContextService = $container->get(UserContext::class);

        /** @var LdapPeopleMapper $mapper */
        $ldapPeopleMapper = $container->get('ldap_people_mapper');

        $controller = new UtilisateurController($userManager);
        $controller->setLdapPeopleMapper($ldapPeopleMapper);
        $controller->setServiceUserContext($userContextService);
        $controller->setOptions($options);
        $controller->setShibService($shibService);
        $controller->setAuthenticationService($authenticationService);
        $controller->setUserMapper($userMapper);

        return $controller;
    }
}