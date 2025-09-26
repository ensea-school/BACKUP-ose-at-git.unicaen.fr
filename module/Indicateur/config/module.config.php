<?php

namespace Indicateur;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'indicateur' => [
            'route'         => '/gestion/indicateur',
            'controller'    => 'Indicateur\Controller\Indicateur',
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'calcul'                  => [
                    'route'       => '/calcul/:typeIndicateur',
                    'action'      => 'calcul',
                    'constraints' => [
                        'typeIndicateur' => '[0-9]*',
                    ],
                ],
                'result'                  => [
                    'route'       => '/result/:indicateur',
                    'action'      => 'result',
                    'constraints' => [
                        'indicateur' => '[0-9]*',
                    ],
                ],
                'export-csv'              => [
                    'route'       => '/export-csv/:indicateur',
                    'action'      => 'export-csv',
                    'constraints' => [
                        'indicateur' => '[0-9]*',
                    ],
                ],
                'abonner'                 => [
                    'route'       => '/abonner/:indicateur',
                    'action'      => 'abonner',
                    'constraints' => [
                        'indicateur' => '[0-9]*',
                    ],
                ],
                'abonnements'             => [
                    'route'  => '/abonnements',
                    'action' => 'abonnements',
                ],
                'envoi-mail-intervenants' => [
                    'route'       => '/envoi-mail-intervenants/:indicateur',
                    'action'      => 'envoi-mail-intervenants',
                    'constraints' => [
                        'indicateur' => '[0-9]*',
                    ],
                ],
                'depassement-charges'     => [
                    'route'        => '/depassement-charges/:intervenant',
                    'action'       => 'depassement-charges',
                    'child_routes' => [
                        'prevu'   => [
                            'route'        => '/prevu',
                            'defaults'     => [
                                'type-volume-horaire-code' => 'PREVU',
                            ],
                            'child_routes' => [
                                's1' => [
                                    'route'    => '/s1',
                                    'defaults' => [
                                        'periode-code' => 'S1',
                                    ],
                                ],
                                's2' => [
                                    'route'    => '/s2',
                                    'defaults' => [
                                        'periode-code' => 'S2',
                                    ],
                                ],
                            ],
                        ],
                        'realise' => [
                            'route'        => '/realise',
                            'defaults'     => [
                                'type-volume-horaire-code' => 'REALISE',
                            ],
                            'child_routes' => [
                                's1' => [
                                    'route'    => '/s1',
                                    'defaults' => [
                                        'periode-code' => 'S1',
                                    ],
                                ],
                                's2' => [
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

    'navigation' => [
        'gestion' => [
            'pages' => [
                'indicateurs' => [
                    'label'    => "Indicateurs",
                    'icon'     => 'fas fa-chart-line',
                    'title'    => "Indicateurs",
                    'route'    => 'indicateur',
                    'resource' => Authorize::controllerResource('Indicateur\Controller\Indicateur', 'index'),
                    'order'    => 10,
                    'color'    => '#217DD8',
                    'pages'    => [
                        'indicateurs' => [
                            'label'    => "Indicateurs",
                            'icon'     => 'fas fa-chart-line',
                            'title'    => "Indicateurs",
                            'route'    => 'indicateur',
                            'resource' => Authorize::controllerResource('Indicateur\Controller\Indicateur', 'index'),
                            'order'    => 10,
                            'color'    => '#217DD8',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => 'Indicateur\Controller\Indicateur',
            'action'     => ['index', 'calcul', 'result', 'abonnements', 'depassement-charges', 'export-csv'],
            'privileges' => [Privileges::INDICATEUR_VISUALISATION],
        ],
        [
            'controller' => 'Indicateur\Controller\Indicateur',
            'action'     => ['abonner'],
            'privileges' => [Privileges::INDICATEUR_ABONNEMENT],
        ],
        [
            'controller' => 'Indicateur\Controller\Indicateur',
            'action'     => ['envoi-mail-intervenants'],
            'privileges' => [Privileges::INDICATEUR_ENVOI_MAIL_INTERVENANTS],
        ],
    ],

    'controllers' => [
        'Indicateur\Controller\Indicateur' => Controller\IndicateurControllerFactory::class,
    ],

    'services'    => [
        Service\IndicateurService::class             => Service\IndicateurServiceFactory::class,
        Service\NotificationIndicateurService::class => Service\NotificationIndicateurServiceFactory::class,
        Processus\IndicateurProcessus::class         => Processus\IndicateurProcessusFactory::class,
        Command\NotifierCommand::class               => Command\NotifierCommandFactory::class,
    ],
    'laminas-cli' => [
        'commands' => [
            'notifier-indicateurs' => Command\NotifierCommand::class,
        ],
    ],


    'forms' => [
    ],
];