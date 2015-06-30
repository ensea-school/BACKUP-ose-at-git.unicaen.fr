<?php

namespace Application;

use Application\Entity\Db\Privilege;

return [
    'router' => [
        'routes' => [
            'droits' => [
                'type'    => 'Literal',
                'options' => [
                    'route' => '/droits',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Droits',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
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
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'droits' => [
                                'label'    => "Droits d'accès",
                                'title'    => "Gestion des droits d'accès",
                                'route'    => 'droits',
                                'resource' => 'controller/Application\Controller\Droits:index',
                                'pages' => [
                                    'roles' => [
                                        'label'  => "Rôles",
                                        'title'  => "Gestion des rôles",
                                        'route'  => 'droits/roles',
                                        'resource' => 'controller/Application\Controller\Droits:roles',
                                        'withtarget' => true,
                                    ],
                                    'privileges' => [
                                        'label'  => "Privilèges",
                                        'title'  => "Gestion des privilèges",
                                        'route'  => 'droits/privileges',
                                        'resource' => 'controller/Application\Controller\Droits:privileges',
                                        'withtarget' => true,
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
            'Application\Guard\PrivilegeController' => [
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['index'],
                    'privileges' => [
                        Privilege::DROIT_ROLE_VISUALISATION,
                        Privilege::DROIT_PRIVILEGE_VISUALISATION,
                        Privilege::DROIT_AFFECTATION_VISUALISATION
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['roles'],
                    'privileges' => [
                        Privilege::DROIT_ROLE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['privileges'],
                    'privileges' => [
                        Privilege::DROIT_PRIVILEGE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['affectations'],
                    'privileges' => [
                        Privilege::DROIT_AFFECTATION_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['role-edition', 'role-suppression'],
                    'privileges' => [Privilege::DROIT_ROLE_EDITION]
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['privileges-modifier'],
                    'privileges' => [Privilege::DROIT_PRIVILEGE_EDITION]
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Droits' => 'Application\Controller\DroitsController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationPerimetre' => 'Application\\Service\\Perimetre',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'DroitsRoleForm'       => 'Application\Form\Droits\RoleForm',
            'DroitsPrivilegesForm' => 'Application\Form\Droits\PrivilegesForm',
        ],
    ],
    'public_files' => [
        'js' => [
            'js/droits.js',
        ],
    ]
];