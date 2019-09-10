<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use UnicaenApp\Mapper\Ldap\People as LdapPeopleMapper;
use UnicaenAuth\Options\ModuleOptions;
use UnicaenAuth\Service\ShibService;
use UnicaenAuth\Service\UserContext;
use Zend\Authentication\AuthenticationService;
use ZfcUser\Mapper\UserInterface;

class UtilisateurControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @return UtilisateurController
     */
    public function __invoke(ContainerInterface $container)
    {
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

        $controller = new UtilisateurController();
        $controller->setLdapPeopleMapper($ldapPeopleMapper);
        $controller->setServiceUserContext($userContextService);
        $controller->setOptions($options);
        $controller->setShibService($shibService);
        $controller->setAuthenticationService($authenticationService);
        $controller->setUserMapper($userMapper);

        return $controller;
    }
}