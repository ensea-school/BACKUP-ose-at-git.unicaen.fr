<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;
use Application\Provider\Privilege\Privileges;

return [
    'router'          => [
        'routes' => [
            'departement' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/departement',
                    'defaults' => [
                        'controller' => 'Application\Controller\Departement',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/saisie[/:departement]',
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/supprimer/:departement',
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
                    'controller' => 'Application\Controller\Departement',
                    'action'     => ['index'],
                    'privileges' => [Privileges::PARAMETRES_DEPARTEMENT_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Departement',
                    'action'     => ['saisie', 'supprimer'],
                    'privileges' => [Privileges::PARAMETRES_DEPARTEMENT_EDITION],
                ],
            ],
        ],

    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Departement' => Controller\DepartementController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\DepartementService::class => Service\DepartementService::class,
        ],
    ],
];
