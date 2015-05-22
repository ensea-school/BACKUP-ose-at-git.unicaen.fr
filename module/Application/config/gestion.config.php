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
                                'child_routes' => [
                                    'edition' => [
                                        'type'    => 'Segment',
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/edition[/:role]',
                                            'constraints' => [
                                                'role' => '[0-9]*',
                                            ],
                                            'defaults' => [
                                                'action' => 'role-edition',
                                            ],
                                        ],
                                    ],
                                    'suppression' => [
                                        'type'    => 'Segment',
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/suppression/:role',
                                            'constraints' => [
                                                'role' => '[0-9]*',
                                            ],
                                            'defaults' => [
                                                'action' => 'role-suppression',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'privileges' => [
                                'type'    => 'Literal',
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/privileges',
                                    'defaults' => [
                                        'action' => 'privileges',
                                    ],
                                ],
                                'child_routes' => [
                                    'modifier' => [
                                        'type'    => 'Segment',
                                        'may_terminate' => true,
                                        'options' => [
                                            'route'    => '/modifier',
                                            'defaults' => [
                                                'action' => 'privileges-modifier',
                                            ],
                                        ],
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
                                        'resource' => 'privilege/privilege-visualisation',
                                    ],
                                    'privileges' => [
                                        'label'  => "Privilèges",
                                        'title'  => "Gestion des privilèges",
                                        'route'  => 'gestion/droits/privileges',
                                        'withtarget' => true,
                                        'resource' => 'privilege/privilege-visualisation',
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
                    'action'     => ['droits', 'roles', 'privileges'],
                    'privileges' => ['privilege-visualisation', 'privilege-edition'],
                ],
                [
                    'controller' => 'Application\Controller\Gestion',
                    'action'     => ['role-edition', 'role-suppression', 'privileges-modifier'],
                    'privileges' => ['privilege-edition']
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Gestion' => 'Application\Controller\GestionController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationPerimetre' => 'Application\\Service\\Perimetre',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'GestionRoleForm'       => 'Application\Form\Gestion\RoleForm',
            'GestionPrivilegesForm' => 'Application\Form\Gestion\PrivilegesForm',
        ],
    ],
    'public_files' => [
        'js' => [
            'js/gestion.js',
        ],
    ]
];