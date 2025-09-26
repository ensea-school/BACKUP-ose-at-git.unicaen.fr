<?php

use Application\Provider\Privilege\Privileges;

return [
    'unicaen-auth' => [
        'privilege_entity_class' => \Application\Entity\Db\Privilege::class,
        'enable_privileges'      => true,
    ],

    'bjyauthorize' => [
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
                        Privileges::DROIT_ROLE_VISUALISATION,
                        Privileges::DROIT_PRIVILEGE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['roles'],
                    'privileges' => [Privileges::DROIT_ROLE_VISUALISATION],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['privileges'],
                    'privileges' => [Privileges::DROIT_PRIVILEGE_VISUALISATION],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['role-edition', 'role-suppression'],
                    'privileges' => [Privileges::DROIT_ROLE_EDITION],
                ],
                [
                    'controller' => 'UnicaenAuth\Controller\Droits',
                    'action'     => ['privileges-modifier'],
                    'privileges' => [Privileges::DROIT_PRIVILEGE_EDITION],
                ],
            ],
        ],
    ],
];