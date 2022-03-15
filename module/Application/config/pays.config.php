<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;
use Application\Provider\Privilege\Privileges;

return [
    'router'          => [
        'routes' => [
            'pays' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/pays',
                    'defaults' => [
                        'controller' => 'Application\Controller\Pays',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'modifier'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/saisie[/:pays]',
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/supprimer/:pays',
                            'defaults' => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Pays',
                    'action'     => ['index'],
                    'privileges' => [Privileges::PARAMETRES_PAYS_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Pays',
                    'action'     => ['saisie', 'supprimer'],
                    'privileges' => [Privileges::PARAMETRES_PAYS_EDITION],
                ],
            ],
        ],

    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Pays' => Controller\PaysController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\PaysService::class => Service\PaysService::class,
        ],
    ],
];
