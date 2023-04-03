<?php

return [
    'unicaen-auth' => [
        'privilege_entity_class' => \Application\Entity\Db\Privilege::class,
        'enable_privileges'      => true,
    ],

    'bjyauthorize' => [
        //'identity_provider' => 'UnicaenAuth\Provider\Identity\Chain',

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

        'resource_providers' => [
            \Application\Service\PrivilegeService::class => [],
        ],
        'rule_providers'     => [
            \UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider::class => [],
        ],

        'guards' => [
            \UnicaenPrivilege\Guard\PrivilegeController::class => [
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['index'],
                    'privileges' => [
                        \Application\Provider\Privilege\Privileges::DROIT_ROLE_VISUALISATION,
                        \Application\Provider\Privilege\Privileges::DROIT_PRIVILEGE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['roles'],
                    'privileges' => [\Application\Provider\Privilege\Privileges::DROIT_ROLE_VISUALISATION],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['privileges'],
                    'privileges' => [\Application\Provider\Privilege\Privileges::DROIT_PRIVILEGE_VISUALISATION],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['role-edition', 'role-suppression'],
                    'privileges' => [\Application\Provider\Privilege\Privileges::DROIT_ROLE_EDITION],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['privileges-modifier'],
                    'privileges' => [\Application\Provider\Privilege\Privileges::DROIT_PRIVILEGE_EDITION],
                ],
            ],
        ],
    ],
];