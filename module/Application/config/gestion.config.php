<?php

namespace Application;

use Application\Entity\Db\Privilege;

return [
    'router' => [
        'routes' => [
            'gestion' => [
                'type'    => 'Literal',
                'options' => [
                    'route' => '/gestion',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Gestion',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'label'  => "Gestion",
                        'route'  => 'gestion',
                        'resource' => 'controller/Application\Controller\Gestion:index',
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'Application\Guard\PrivilegeController' => [
                [
                    'controller' => 'Application\Controller\Gestion',
                    'action'     => ['index'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                    'privileges' => [
                        Privilege::MISE_EN_PAIEMENT_EXPORT_PAIE,
                        Privilege::MISE_EN_PAIEMENT_VISUALISATION,
                        Privilege::DROIT_ROLE_VISUALISATION,
                        Privilege::DROIT_PRIVILEGE_VISUALISATION,
                        Privilege::DROIT_AFFECTATION_VISUALISATION,
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Gestion' => 'Application\Controller\GestionController',
        ],
    ],
];