<?php

namespace Utilisateur;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'droits' => [
            'route'         => '/droits',
            'controller'    => Controller\DroitsController::class,
            'action'        => 'index',
            'privileges'    => [
                Privileges::DROIT_ROLE_VISUALISATION,
                Privileges::DROIT_PRIVILEGE_VISUALISATION,
                Privileges::DROIT_AFFECTATION_VISUALISATION,
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'roles'        => [
                    'route'         => '/roles',
                    'controller'    => Controller\DroitsController::class,
                    'action'        => 'roles',
                    'privileges'    => [
                        Privileges::DROIT_ROLE_VISUALISATION,
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'edition'     => [
                            'route'       => '/edition[/:role]',
                            'controller'  => Controller\DroitsController::class,
                            'action'      => 'role-edition',
                            'privileges'  => [Privileges::DROIT_ROLE_EDITION],
                            'constraints' => [
                                'role' => '[0-9]*',
                            ],
                        ],
                        'suppression' => [
                            'route'       => '/suppression/:role',
                            'controller'  => Controller\DroitsController::class,
                            'action'      => 'role-suppression',
                            'privileges'  => [Privileges::DROIT_ROLE_EDITION],
                            'constraints' => [
                                'role' => '[0-9]*',
                            ],
                        ],
                    ],
                ],
                'privileges'   => [
                    'route'         => '/privileges',
                    'controller'    => Controller\DroitsController::class,
                    'action'        => 'privileges',
                    'privileges'    => [
                        Privileges::DROIT_PRIVILEGE_VISUALISATION,
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'modifier' => [
                            'may_terminate' => true,
                            'route'         => '/modifier',
                            'controller'    => Controller\DroitsController::class,
                            'action'        => 'privileges-modifier',
                            'privileges'    => [Privileges::DROIT_PRIVILEGE_EDITION],
                        ],
                    ],
                ],
                'affectations' => [
                    'route'         => '/affectations',
                    'controller'    => Controller\DroitsController::class,
                    'action'        => 'affectations',
                    'privileges'    => [
                        Privileges::DROIT_AFFECTATION_VISUALISATION,
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'edition'     => [
                            'route'       => '/edition[/:affectation]',
                            'controller'  => Controller\DroitsController::class,
                            'action'      => 'affectation-edition',
                            'privileges'  => [Privileges::DROIT_AFFECTATION_EDITION],
                            'constraints' => [
                                'affectation' => '[0-9]*',
                            ],
                        ],
                        'suppression' => [
                            'route'       => '/suppression/:affectation',
                            'controller'  => Controller\DroitsController::class,
                            'action'      => 'affectation-suppression',
                            'privileges'  => [Privileges::DROIT_AFFECTATION_EDITION],
                            'constraints' => [
                                'affectation' => '[0-9]*',
                            ],

                        ],
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
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
                            'resource'    => Authorize::controllerResource(Controller\DroitsController::class, 'affectations'),
                        ],
                        'privileges'   => [
                            'label'       => "Privilèges",
                            'title'       => "Administration des privilèges",
                            'description' => 'Tableau de bord listant, par rôle, les privilèges qui lui sont accordés. Le tableau permet également, si vous en avez le droit, de modifier les privilèges accordés par rôle.',
                            'route'       => 'droits/privileges',
                            'order'       => 20,
                            'resource'    => Authorize::controllerResource(Controller\DroitsController::class, 'privileges'),
                        ],
                        'roles'        => [
                            'label'       => "Rôles",
                            'title'       => "Administration des rôles",
                            'description' => 'Permet de visualiser les rôles existants. Permet également de les modifier, d\'en ajouter ou d\'en supprimer si vous avez les droits requis pour cela.',
                            'route'       => 'droits/roles',
                            'order'       => 30,
                            'resource'    => Authorize::controllerResource(Controller\DroitsController::class, 'roles'),
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\DroitsController::class => Controller\DroitsControllerFactory::class,
    ],

    'services' => [
        Service\AffectationService::class => Service\AffectationServiceFactory::class,
        Service\RoleService::class        => Service\RoleServiceFactory::class,
        Service\UtilisateurService::class => Service\UtilisateurServiceFactory::class,

        \UnicaenPrivilege\Service\Privilege\PrivilegeService::class => Service\PrivilegeServiceFactory::class,
        Connecteur\LdapConnecteur::class                            => Connecteur\LdapConnecteurFactory::class,
        Service\PrivilegeService::class                             => Service\PrivilegeServiceFactory::class,
    ],

    'forms' => [
        Form\RoleForm::class        => Form\RoleFormFactory::class,
        Form\AffectationForm::class => Form\AffectationFormFactory::class,
    ],

    'view_helpers' => [
        'utilisateur' => View\Helper\UtilisateurViewHelperFactory::class,
    ],
];