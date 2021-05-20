<?php

namespace UnicaenSiham;


use UnicaenSiham\Controller\Factory\IndexControllerFactory;
use UnicaenSiham\Controller\IndexController;
use UnicaenSiham\Service\Factory\SihamClientFactory;
use UnicaenSiham\Service\Factory\SihamFactory;
use UnicaenSiham\Service\Siham;
use UnicaenSiham\Service\SihamClient;


return [

    'router'          => [
        'routes' => [
            'siham' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/siham',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'voir'                   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/voir-agent/:matricule',
                            'defaults' => [
                                'action' => 'voir',
                            ],
                        ],

                    ],
                    'historiser-adresse'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/historiser-adresse/:matricule',
                            'defaults' => [
                                'action' => 'historiser-adresse-agent',
                            ],
                        ],

                    ],
                    'voir-nomenclature'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/voir-nomenclature/:nomenclature',
                            'defaults' => [
                                'action' => 'voir-nomenclature',
                            ],
                        ],

                    ],
                    'renouveller-agent'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/renouveller-agent/:matricule',
                            'defaults' => [
                                'action' => 'renouveller-agent',
                            ],
                        ],

                    ],
                    'prise-en-charge-agent'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/prise-en-charge-agent/:intervenant',
                            'defaults' => [
                                'action' => 'prise-en-charge-agent',
                            ],
                        ],

                    ],
                    'liste-intervenants-pec' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/liste-intervenant-pec',
                            'defaults' => [
                                'action' => 'liste-intervenants-pec',
                            ],
                        ],

                    ],
                ],
            ],

        ],
    ],
    'bjyauthorize'    => [
        'guards' => [
            \BjyAuthorize\Guard\Controller::class => [
                [
                    'controller' => IndexController::class,
                    'action'     => [
                        'index',
                        'voir',
                        'voir-nomenclature',
                        'historiser-adresse-agent',
                        'renouveller-agent',
                        'prise-en-charge-agent',
                        'liste-intervenants-pec',
                    ],
                    'roles'      => ['guest'],

                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            SihamClient::class => SihamClientFactory::class,
            Siham::class       => SihamFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
