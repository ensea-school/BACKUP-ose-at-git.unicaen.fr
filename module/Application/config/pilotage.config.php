<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use Service\Controller\ModificationServiceDuController;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'pilotage' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/pilotage',
                    'defaults' => [
                        'controller' => 'Application\Controller\Pilotage',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'ecarts-etats' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/ecarts-etats',
                            'defaults' => [
                                'action' => 'ecartsEtats',
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
                    'gestion' => [
                        'pages' => [
                            'pilotage' => [
                                'label'    => 'Pilotage',
                                'title'    => 'Pilotage',
                                'icon'     => 'fas fa-chart-line',
                                'route'    => 'pilotage',
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Pilotage', 'index'),
                                'pages'    => [
                                    'ecarts-etats'                       => [
                                        'label'       => 'Ecarts d\'heures complémentaires (CSV)',
                                        'title'       => 'Ecarts d\'heures complémentaires (CSV)',
                                        'description' => 'Export CSV des HETD (ne porte que sur les heures complémentaires et non sur le service dû)',
                                        'route'       => 'pilotage/ecarts-etats',
                                        'resource'    => PrivilegeController::getResourceId('Application\Controller\Pilotage', 'ecartsEtats'),
                                    ],
                                    'modification-service-du-export-csv' => [
                                        'label'    => "Modifications de service dû (CSV)",
                                        'title'    => "Modifications de service dû (CSV)",
                                        'route'    => 'modification-service-du/export-csv',
                                        'resource' => PrivilegeController::getResourceId(ModificationServiceDuController::class, 'export-csv'),
                                    ],
                                ],
                                'order'    => 20,
                                'color'    => '#00A020',
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
                    'controller' => 'Application\Controller\Pilotage',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::PILOTAGE_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Pilotage',
                    'action'     => ['ecartsEtats'],
                    'privileges' => [
                        Privileges::PILOTAGE_ECARTS_ETATS,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\PilotageService::class => Service\PilotageService::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Pilotage' => Controller\PilotageController::class,
        ],
    ],
];