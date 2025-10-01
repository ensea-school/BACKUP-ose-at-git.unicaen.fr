<?php

namespace Utilisateur\Controller;

use Framework\User\UserManager;
use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerInterface;
use UnicaenApp\Mapper\Ldap\People as LdapPeopleMapper;
use UnicaenAuthentification\Options\ModuleOptions;
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

        /** @var AuthenticationService $authenticationService */
        $authenticationService = $container->get(AuthenticationService::class);

        /** @var ModuleOptions $options */
        $options = $container->get('unicaen-auth_module_options');

        /** @var ShibService $shibService */
        $shibService = $container->get(ShibService::class);

        /** @var UserContext $userContextService */
        $userContextService = $container->get('AuthUserContext');

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