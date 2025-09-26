<?php

namespace Utilisateur\Connecteur;

use Framework\Application\Application;
use Psr\Container\ContainerInterface;
use UnicaenAuthentification\Service\UserContext;


/**
 * Description of LdapConnecteurFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class LdapConnecteurFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return LdapConnecteur
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $serviceUserContext = $container->get(UserContext::class);
        $mapperStructure    = $container->get('ldap_structure_mapper');
        $mapperPeople       = $container->get('ldap_people_mapper');
        $mapperUser         = $container->get('zfcuser_user_mapper');

        $service = new LdapConnecteur(
            $serviceUserContext,
            $mapperStructure,
            $mapperPeople,
            $mapperUser
        );

        $config = $container->get('Config');
        if (isset($config['unicaen-app']['ldap']['utilisateur'])) {
            $configLdapUtilisateur = $config['unicaen-app']['ldap']['utilisateur'];
        } else {
            $configLdapUtilisateur = [];
        }

        if (isset($configLdapUtilisateur['LOGIN'])) {
            $service->setUtilisateurLogin($configLdapUtilisateur['LOGIN']);
        }

        if (isset($configLdapUtilisateur['FILTER'])) {
            $service->setUtilisateurFiltre($configLdapUtilisateur['FILTER']);
        }

        if (isset($configLdapUtilisateur['CODE'])) {
            $service->setUtilisateurCode($configLdapUtilisateur['CODE']);
        }

        if (isset($configLdapUtilisateur['CODEFILTER'])) {
            $service->setUtilisateurCodeFiltre($configLdapUtilisateur['CODEFILTER']);
        }

        $service->setUtilisateurExtraMasque(Application::getInstance()->config()['ldap']['utilisateurExtraMasque'] ?? '');
        $service->setUtilisateurExtraAttributes(Application::getInstance()->config()['ldap']['utilisateurExtraAttributes'] ?? []);

        return $service;
    }
}