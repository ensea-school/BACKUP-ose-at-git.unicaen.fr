<?php
/**
 * UnicaenAuth Global Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = [
    /**
     * Flag indiquant si l'utilisateur authenitifié avec succès via l'annuaire LDAP doit
     * être enregistré/mis à jour dans la table des utilisateurs de l'appli.
     */
    'save_ldap_user_in_database' => true,
    /**
     * Enable registration
     * Allows users to register through the website.
     * Accepted values: boolean true or false
     */
    'enable_registration' => false,

    'enable_privileges' => true,

    'entity_manager_name' => 'doctrine.entitymanager.orm_default', // nom du gestionnaire d'entités à utiliser
];

$config = [
    'unicaen-auth' => $settings,
    'bjyauthorize' => [
        /* this module uses a meta-role that inherits from any roles that should
                 * be applied to the active user. the identity provider tells us which
                 * roles the "identity role" should inherit from.
                 *
                 * for ZfcUser, this will be your default identity provider
                 */
        //'identity_provider' => 'UnicaenAuth\Provider\Identity\Chain',

        /* role providers simply provide a list of roles that should be inserted
         * into the Zend\Acl instance. the module comes with two providers, one
         * to specify roles in a config file and one to load roles using a
         * Zend\Db adapter.
         */
        'role_providers' => [
            /**
             * Fournit les rôles issus de la base de données éventuelle de l'appli.
             * NB: si le rôle par défaut 'guest' est fourni ici, il ne sera pas ajouté en double dans les ACL.
             * NB: si la connexion à la base échoue, ce n'est pas bloquant!
             */
            //'UnicaenAuth\Provider\Role\DbRole'   => [],
            /**
             * Fournit le rôle correspondant à l'identifiant de connexion de l'utilisateur.
             * Cela est utile lorsque l'on veut gérer les habilitations d'un utilisateur unique
             * sur des ressources.
             */
            //'UnicaenAuth\Provider\Role\Username' => [],
        ],
    ],
    'zfcuser'      => [
        $k = 'enable_registration' => isset($settings[$k]) ? $settings[$k] : false,
    ],
];

if ($settings['enable_privileges']) {
    $privileges = [
        'bjyauthorize' => [

            'resource_providers' => [
                /**
                 * Le service Privilèges peut aussi être une source de ressources,
                 * si on souhaite tester directement l'accès à un privilège
                 */
                'UnicaenAuth\Service\Privilege' => [],
            ],

            'rule_providers' => [
                'UnicaenAuth\Provider\Rule\PrivilegeRuleProvider' => [],
            ],

            'guards' => [
                'UnicaenAuth\Guard\PrivilegeController' => [
                    [
                        'controller' => 'UnicaenAuth\Controller\Droits',
                        'action'     => ['index'],
                        'privileges' => [
                            \UnicaenAuth\Provider\Privilege\Privileges::DROIT_ROLE_VISUALISATION,
                            \UnicaenAuth\Provider\Privilege\Privileges::DROIT_PRIVILEGE_VISUALISATION,
                        ],
                    ],
                    [
                        'controller' => 'UnicaenAuth\Controller\Droits',
                        'action'     => ['roles'],
                        'privileges' => [\UnicaenAuth\Provider\Privilege\Privileges::DROIT_ROLE_VISUALISATION],
                    ],
                    [
                        'controller' => 'UnicaenAuth\Controller\Droits',
                        'action'     => ['privileges'],
                        'privileges' => [\UnicaenAuth\Provider\Privilege\Privileges::DROIT_PRIVILEGE_VISUALISATION],
                    ],
                    [
                        'controller' => 'UnicaenAuth\Controller\Droits',
                        'action'     => ['role-edition', 'role-suppression'],
                        'privileges' => [\UnicaenAuth\Provider\Privilege\Privileges::DROIT_ROLE_EDITION],
                    ],
                    [
                        'controller' => 'UnicaenAuth\Controller\Droits',
                        'action'     => ['privileges-modifier'],
                        'privileges' => [\UnicaenAuth\Provider\Privilege\Privileges::DROIT_PRIVILEGE_EDITION],
                    ],
                ],
            ],
        ],
    ];
} else {
    $privileges = [];
}

return array_merge_recursive($config, $privileges);