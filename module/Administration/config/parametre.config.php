<?php

namespace Administration;

use Application\Provider\Privileges;

return [
    'routes' => [
        'parametres' => [
            'route'         => '/parametres',
            'child_routes'  => [
                'generaux' => [
                    'route'      => '/generaux',
                    'controller' => Controller\ParametreController::class,
                    'action'     => 'generaux',
                    'privileges' => [
                        Privileges::PARAMETRES_GENERAL_VISUALISATION,
                    ],
                ],
                'annees'   => [
                    'route'      => '/annees',
                    'controller' => Controller\ParametreController::class,
                    'action'     => 'annees',
                    'privileges' => [
                        Privileges::PARAMETRES_ANNEES_VISUALISATION,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'configuration' => [
                    'pages' => [
                        'annees'   => [
                            'label'    => "Années",
                            'route'    => 'parametres/annees',
                            'order'    => 10,
                        ],
                        'generaux' => [
                            'label'    => "Paramètres généraux",
                            'route'    => 'parametres/generaux',
                            'order'    => 40,
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\ParametreController::class => Controller\ParametreControllerFactory::class,
    ],

    'forms' => [
        Form\ParametresForm::class => Form\ParametresFormFactory::class,
    ],

    'services' => [
        Service\ParametresService::class => Service\ParametresServiceFactory::class,
    ],
];

