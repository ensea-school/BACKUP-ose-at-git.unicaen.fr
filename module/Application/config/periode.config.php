<?php

namespace Application;


use Application\Provider\Privileges;
use Application\Service\PeriodeService;
use Framework\Authorize\Authorize;

return [
    'router' => [
        'routes' => [
            'periodes' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/periodes',
                    'defaults' => [
                        'controller' => Controller\PeriodeController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:periode]',
                            'constraints' => [
                                'periode' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer/:periode',
                            'constraints' => [
                                'periode' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'trier'     => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/trier',
                            'constraints' => [
                            ],
                            'defaults'    => [
                                'action' => 'trier',
                            ],
                        ],
                        'may_terminate' => true,
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
                            'finances' => [
                                'pages' => [
                                    'gestion-periode' => [
                                        'label'          => 'Périodes',
                                        'route'          => 'periodes',
                                        'resource'       => Authorize::controllerResource(Controller\PeriodeController::class, 'index'),
                                        'order'          => 30,
                                        'border - color' => '#111',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],


    'guards'          => [
        [
            'controller' => Controller\PeriodeController::class,
            'action'     => ['index'],
            'privileges' => [Privileges::PARAMETRES_PERIODES_VISUALISATION],
        ],
        [
            'controller' => Controller\PeriodeController::class,
            'action'     => ['saisie', 'supprimer', 'trier'],
            'privileges' => [Privileges::PARAMETRES_PERIODES_EDITION],
        ],
    ],


    'controllers'     => [
        'factories' => [
            Controller\PeriodeController::class => Controller\Factory\PeriodeControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            PeriodeService::class => PeriodeService::class,
        ],
    ],

    'form_elements' => [
        'factories' => [
            Form\Periode\PeriodeSaisieForm::class => Form\Periode\PeriodeSaisieFormFactory::class,
        ],
    ],
];
