<?php

namespace ExportRh;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [

    'export-rh' => [
        'siham-ws' => [],
    ],

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
                    'action'     => ['index'],
                    'privileges' => [Privileges::INTERVENANT_STATUT_VISUALISATION],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\ExportRhService::class          => Service\ExportRhServiceFactory::class,
            Connecteur\Siham\SihamConnecteur::class => Connecteur\Siham\SihamConnecteurFactory::class,
        ],
    ],
    'view_helpers'    => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            //'ExportRh\Controller\Index' => Controller\IndexControllerFactory::class,
            Controller\AdministrationController::class => Controller\AdministrationControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
