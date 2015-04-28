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
                        'child_routes' => [
                            'roles' => [
                                'type'    => 'Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/roles',
                                    'defaults' => [
                                        'action' => 'roles',
                                    ],
                                ],
                            ],
                            'privileges' => [
                                'type'    => 'Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/privileges[/:role]',
                                    'defaults' => [
                                        'action' => 'privileges',
                                    ],
                                ],
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
                                'pages' => [
                                    'roles' => [
                                        'label'  => "Rôles",
                                        'title'  => "Gestion des rôles",
                                        'route'  => 'gestion/droits/roles',
                                        'withtarget' => true,
                                        'resource' => 'controller/Application\Controller\Gestion:roles',
                                    ],
                                    'privileges' => [
                                        'label'  => "Privilèges",
                                        'title'  => "Privilèges par rôle",
                                        'route'  => 'gestion/droits/privileges',
                                        'withtarget' => true,
                                        'resource' => 'controller/Application\Controller\Gestion:privileges',
                                    ],
                                ],
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
                    'action'     => ['droits', 'privileges', 'roles'],
                    'privileges' => ['privilege-visualisation', 'privilege-edition']
                ]
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Gestion' => 'Application\Controller\GestionController',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'GestionPrivilegesForm' => 'Application\Form\Gestion\PrivilegesForm',
        ],
    ],
];