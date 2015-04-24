<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'gestion' => [
                'type'    => 'Literal',
                'options' => [
                    'route' => '/gestion',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'gestion',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'droits' => [
                        'type'    => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/droits',
                            'defaults' => [
                                'action' => 'droits',
                                'controller' => 'Gestion',
                            ],
                        ],
                    ],
                ],
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
                        'resource' => 'controller/Application\Controller\Index:gestion',
                        'pages' => [
                            'droits' => [
                                'label'    => "Droits d'accès",
                                'title'    => "Gestion des droits d'accès",
                                'route'    => 'gestion/droits',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Index',
                    'action'     => ['gestion'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                ],
            ],
            'Application\Guard\PrivilegeController' => [
                [
                    'controller' => 'Application\Controller\Gestion',
                    'action'     => ['droits'],
                    'privileges' => ['privilege-visualisation', 'privilege-edition']
                ]
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Gestion'   => 'Application\Controller\GestionController',
        ],
    ],
];