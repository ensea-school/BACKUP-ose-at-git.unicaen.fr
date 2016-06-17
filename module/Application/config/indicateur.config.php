<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'indicateur' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/gestion/indicateur',
                    'defaults' => [
                        'controller'    => 'Application\Controller\Indicateur',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'result' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/result/:indicateur[/structure/:structure]',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                                'structure'  => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'result',
                            ],
                        ],
                    ],
                    'details' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:indicateur[/structure/:structure]',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                                'structure'  => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'details',
                            ],
                        ],
                    ],
                    'abonner' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/abonner/:indicateur',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'abonner',
                            ],
                        ],
                    ],
                    'abonnements' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/abonnements/:personnel',
                            'constraints' => [
                                'personnel' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'abonnements',
                            ],
                        ],
                    ],
                    'result-item' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/result-item/:action/:intervenant',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
                            ],
                        ],
                    ],
                    'purger-indicateur-donnees-perso-modif' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/purger-indicateur-donnees-perso-modif/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'purger-indicateur-donnees-perso-modif',
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
                            'indicateurs' => [
                                'label'    => "Indicateurs",
                                'icon'     => 'fa fa-line-chart',
                                'border-color' => '#E5272E',
                                'title'    => "Indicateurs",
                                'route'    => 'indicateur',
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Indicateur','index'),
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Indicateur',
                    'action'     => [
                        'index',
                        'result','details',
                        'abonner',
                        'abonnements',
                        'result-item-donnees-perso-diff-import',
                        'result-item-donnees-perso-modif',
                        'purger-indicateur-donnees-perso-modif'
                    ],
                    'roles'      => ['user'],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Indicateur' => Controller\IndicateurController::class,
        ],
    ],
    'service_manager' => [
        'invokables'   => [
            'applicationIndicateur'                    => Service\IndicateurService::class,
            'NotificationIndicateurService'            => Service\NotificationIndicateur::class,
        ],
    ],
];