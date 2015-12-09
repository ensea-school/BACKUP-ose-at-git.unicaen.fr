<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

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
                    'affectations' => [
                        'type'    => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/affectations',
                            'defaults' => [
                                'action' => 'affectations',
                            ],
                        ],
                        'child_routes' => [
                            'edition' => [
                                'type'    => 'Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/edition[/:affectation]',
                                    'constraints' => [
                                        'affectation' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'affectation-edition',
                                    ],
                                ],
                            ],
                            'suppression' => [
                                'type'    => 'Segment',
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/suppression/:affectation',
                                    'constraints' => [
                                        'affectation' => '[0-9]*',
                                    ],
                                    'defaults' => [
                                        'action' => 'affectation-suppression',
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
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Droits','index'),
                                'pages' => [
                                    'roles' => [
                                        'label'  => "Rôles",
                                        'title'  => "Gestion des rôles",
                                        'route'  => 'droits/roles',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Droits','roles'),
                                        'withtarget' => true,
                                    ],
                                    'privileges' => [
                                        'label'  => "Privilèges",
                                        'title'  => "Gestion des privilèges",
                                        'route'  => 'droits/privileges',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Droits','privileges'),
                                        'withtarget' => true,
                                    ],
                                    'affectations' => [
                                        'label'  => "Affectations",
                                        'title'  => "Gestion des affectations",
                                        'route'  => 'droits/affectations',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Droits','affectations'),
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
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::DROIT_ROLE_VISUALISATION,
                        Privileges::DROIT_PRIVILEGE_VISUALISATION,
                        Privileges::DROIT_AFFECTATION_VISUALISATION
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['roles'],
                    'privileges' => [
                        Privileges::DROIT_ROLE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['privileges'],
                    'privileges' => [
                        Privileges::DROIT_PRIVILEGE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['affectations'],
                    'privileges' => [
                        Privileges::DROIT_AFFECTATION_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['role-edition', 'role-suppression'],
                    'privileges' => [Privileges::DROIT_ROLE_EDITION]
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['privileges-modifier'],
                    'privileges' => [Privileges::DROIT_PRIVILEGE_EDITION]
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['affectation-edition', 'affectation-suppression'],
                    'privileges' => [Privileges::DROIT_AFFECTATION_EDITION]
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Droits' => Controller\DroitsController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationPerimetre' => Service\Perimetre::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'DroitsRoleForm'       => Form\Droits\RoleForm::class,
            'DroitsAffectationForm'=> Form\Droits\AffectationForm::class,
        ],
    ],
    'public_files' => [
        'js' => [
            'js/droits.js',
        ],
    ]
];