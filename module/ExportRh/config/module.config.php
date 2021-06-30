<?php

namespace ExportRh;

use Application\Provider\Privilege\Privileges;
use ExportRh\Connecteur\Siham\SihamConnecteur;
use ExportRh\Connecteur\Siham\SihamConnecteurFactory;
use UnicaenAuth\Guard\PrivilegeController;

return [

    'router' => [
        'routes' => [
            'export-rh' => [
                'type'          => 'Segment',
                'may_terminate' => false,
                'options'       => [
                    'route' => '/export-rh',
                ],
                'child_routes'  => [
                    'administration' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/administration',
                            'defaults' => [
                                'controller' => Controller\AdministrationController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'child_routes'  => [
                            'chercher-intervenant-rh' => [
                                'type'          => 'Literal',
                                'may_terminate' => true,
                                'options'       => [
                                    'route'    => '/chercher-intervenant-rh',
                                    'defaults' => [
                                        'controller' => Controller\AdministrationController::class,
                                        'action'     => 'chercher-intervenant-rh',
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
                    'administration' => [
                        'pages' => [
                            'export-rh' => [
                                'label'          => 'Export vers le SI RH',
                                'icon'           => 'glyphicon glyphicon-list-alt',
                                'route'          => 'export-rh/administration',
                                'resource'       => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
                                'order'          => 82,
                                'border - color' => '#111',
                                'pages'          => [
                                    'chercher-intervenant-rh' => [
                                        'label'        => 'Rechercher un intervenant dans le SI RH',
                                        'icon'         => 'fa fa-graduation-cap',
                                        'route'        => 'export-rh/administration/chercher-intervenant-rh',
                                        'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'chercher-intervenant-rh'),
                                        'order'        => 800,
                                        'border-color' => '#BBCF55',
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
                    'controller' => Controller\AdministrationController::class,
                    'action'     => ['index', 'chercher-intervenant-rh'],
                    'privileges' => [Privileges::INTERVENANT_STATUT_VISUALISATION],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\ExportRhService::class => Service\ExportRhServiceFactory::class,
            SihamConnecteur::class         => SihamConnecteurFactory::class,
        ],
    ],
    'view_helpers'    => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            Controller\AdministrationController::class => Controller\AdministrationControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
