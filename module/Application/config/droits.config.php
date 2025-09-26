<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'droits' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/droits',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Application\Controller\Droits',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'roles'        => [
                        'type'          => 'Segment',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/roles',
                            'defaults' => [
                                'action' => 'roles',
                            ],
                        ],
                        'child_routes'  => [
                            'edition'     => [
                                'type'          => 'Segment',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'       => '/edition[/:role]',
                                    'constraints' => [
                                        'role' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'role-edition',
                                    ],
                                ],
                            ],
                            'suppression' => [
                                'type'          => 'Segment',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'       => '/suppression/:role',
                                    'constraints' => [
                                        'role' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'role-suppression',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'privileges'   => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/privileges',
                            'defaults' => [
                                'action' => 'privileges',
                            ],
                        ],
                        'child_routes'  => [
                            'modifier' => [
                                'type'          => 'Segment',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'    => '/modifier',
                                    'defaults' => [
                                        'action' => 'privileges-modifier',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'affectations' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/affectations',
                            'defaults' => [
                                'action' => 'affectations',
                            ],
                        ],
                        'child_routes'  => [
                            'edition'     => [
                                'type'          => 'Segment',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'       => '/edition[/:affectation]',
                                    'constraints' => [
                                        'affectation' => '[0-9]*',
                                    ],
                                    'defaults'    => [
                                        'action' => 'affectation-edition',
                                    ],
                                ],
                            ],
                            'suppression' => [
                                'type'          => 'Segment',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'       => '/suppression/:affectation',
                                    'constraints' => [
                                        'affectation' => '[0-9]*',
                                    ],
                                    'defaults'    => [
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
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'droits' => [
                                'pages' => [
                                    'affectations' => [
                                        'label'       => "Affectations",
                                        'title'       => "Administration des affectations",
                                        'description' => 'Permet de visualiser les affectations existantes et de les modifier le cas échéant. Pour rappel, une affectation est la liaison entre un rôle et un personnel.',
                                        'route'       => 'droits/affectations',
                                        'order'       => 10,
                                        'resource'    => Authorize::controllerResource('Application\Controller\Droits', 'affectations'),
                                    ],
                                    'privileges'   => [
                                        'label'       => "Privilèges",
                                        'title'       => "Administration des privilèges",
                                        'description' => 'Tableau de bord listant, par rôle, les privilèges qui lui sont accordés. Le tableau permet également, si vous en avez le droit, de modifier les privilèges accordés par rôle.',
                                        'route'       => 'droits/privileges',
                                        'order'       => 20,
                                        'resource'    => Authorize::controllerResource('Application\Controller\Droits', 'privileges'),
                                    ],
                                    'roles'        => [
                                        'label'       => "Rôles",
                                        'title'       => "Administration des rôles",
                                        'description' => 'Permet de visualiser les rôles existants. Permet également de les modifier, d\'en ajouter ou d\'en supprimer si vous avez les droits requis pour cela.',
                                        'route'       => 'droits/roles',
                                        'order'       => 30,
                                        'resource'    => Authorize::controllerResource('Application\Controller\Droits', 'roles'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::DROIT_ROLE_VISUALISATION,
                        Privileges::DROIT_PRIVILEGE_VISUALISATION,
                        Privileges::DROIT_AFFECTATION_VISUALISATION,
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
                    'privileges' => [Privileges::DROIT_ROLE_EDITION],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['privileges-modifier'],
                    'privileges' => [Privileges::DROIT_PRIVILEGE_EDITION],
                ],
                [
                    'controller' => 'Application\Controller\Droits',
                    'action'     => ['affectation-edition', 'affectation-suppression'],
                    'privileges' => [Privileges::DROIT_AFFECTATION_EDITION],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\Droits' => Controller\Factory\DroitsControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\PerimetreService::class => Service\PerimetreService::class,

        ],
        'factories' => [
            Service\AffectationService::class => Service\Factory\AffectationServiceFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories'  => [
            \UnicaenUtilisateur\Form\Role\RoleForm::class => Form\Droits\RoleFormFactory::class,
        ],
        'invokables' => [
            Form\Droits\AffectationForm::class => Form\Droits\AffectationForm::class,
        ],
    ],
];