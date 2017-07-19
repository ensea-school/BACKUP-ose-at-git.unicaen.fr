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

    'navigation'      => [
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

    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => ['index', 'result', 'abonnements'],
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

    'controllers'     => [
        'factories' => [
            'Application\Controller\Indicateur' => Controller\Factory\IndicateurControllerFactory::class,
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'applicationIndicateur'         => Service\IndicateurService::class,
            'NotificationIndicateurService' => Service\NotificationIndicateur::class,
        ],
        'factories' => [
            'processusIndicateur'           => Processus\Factory\IndicateurProcessusFactory::class,
        ],
    ],
];