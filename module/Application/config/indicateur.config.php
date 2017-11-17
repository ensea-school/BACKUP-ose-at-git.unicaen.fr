<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'indicateur' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/gestion/indicateur',
                    'defaults' => [
                        'controller' => 'Application\Controller\Indicateur',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'result'                  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/result/:indicateur[/structure/:structure]',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                                'structure'  => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'result',
                            ],
                        ],
                    ],
                    'abonner'                 => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/abonner/:indicateur',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'abonner',
                            ],
                        ],
                    ],
                    'abonnements'             => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/abonnements',
                            'defaults' => [
                                'action' => 'abonnements',
                            ],
                        ],
                    ],
                    'envoi-mail-intervenants' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/envoi-mail-intervenants/:indicateur',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'envoi-mail-intervenants',
                            ],
                        ],
                    ],
                    'depassement-charges'     => [
                        'type'         => 'Segment',
                        'options'      => [
                            'route'       => '/depassement-charges/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'depassement-charges',
                            ],
                        ],
                        'child_routes' => [
                            'prevu' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/prevu',
                                    'defaults' => [
                                        'type-volume-horaire-code' => 'PREVU',
                                    ],
                                ],
                                'child_routes' => [
                                    's1'   => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/s1',
                                            'defaults' => [
                                                'periode-code' => 'S1',
                                            ],
                                        ],
                                    ],
                                    's2'   => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/s2',
                                            'defaults' => [
                                                'periode-code' => 'S2',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'realise' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/realise',
                                    'defaults' => [
                                        'type-volume-horaire-code' => 'REALISE',
                                    ],
                                ],
                                'child_routes' => [
                                    's1'   => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/s1',
                                            'defaults' => [
                                                'periode-code' => 'S1',
                                            ],
                                        ],
                                    ],
                                    's2'   => [
                                        'type'    => 'Literal',
                                        'options' => [
                                            'route'    => '/s2',
                                            'defaults' => [
                                                'periode-code' => 'S2',
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

    'console' => [
        'router' => [
            'routes' => [
                'notifier-indicateurs' => [
                    'options' => [
                        'route'    => 'notifier indicateurs [--force]',
                        'defaults' => [
                            'controller' => 'Application\Controller\Indicateur',
                            'action'     => 'envoi-notifications',
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
                            'indicateurs' => [
                                'label'        => "Indicateurs",
                                'icon'         => 'fa fa-line-chart',
                                'title'        => "Indicateurs",
                                'route'        => 'indicateur',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Indicateur', 'index'),
                                'order'        => 10,
                                'border-color' => '#217DD8',
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
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => ['index', 'result', 'abonnements', 'depassement-charges'],
                    'privileges' => [Privileges::INDICATEUR_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => ['abonner'],
                    'privileges' => [Privileges::INDICATEUR_ABONNEMENT],
                ],
                [
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => ['envoi-mail-intervenants'],
                    'privileges' => [Privileges::INDICATEUR_ENVOI_MAIL_INTERVENANTS],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            'Application\Controller\Indicateur' => Controller\Factory\IndicateurControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'applicationIndicateur'         => Service\IndicateurService::class,
            'NotificationIndicateurService' => Service\NotificationIndicateur::class,
        ],
        'factories'  => [
            'processusIndicateur' => Processus\Factory\IndicateurProcessusFactory::class,
        ],
    ],
];