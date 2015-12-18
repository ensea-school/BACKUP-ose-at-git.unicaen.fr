<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'pilotage' => [
                'type'          => 'Literal',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/pilotage',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Pilotage',
                        'action'        => 'index',
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
                                'label'        => 'Pilotage',
                                'title'        => 'Pilotage',
                                'icon'         => 'fa fa-tachometer',
                                'border-color' => '#00A020',
                                'route'        => 'pilotage',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Pilotage', 'index'),
                                'pages'        => [
                                    'ecarts-etats' => [
                                        'label'       => 'Ecarts d\'heures',
                                        'title'       => 'Ecarts d\'heures',
                                        'description' => 'Export CSV des HETD',
                                        'route'       => 'pilotage/ecarts-etats',
                                        'resource'    => PrivilegeController::getResourceId('Application\Controller\Pilotage', 'ecartsEtats'),
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
            'ApplicationPilotage' => Service\PilotageService::class,
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Pilotage' => Controller\PilotageController::class,
        ],
    ],
];