<?php

namespace Lieu;

use Application\Provider\Privileges;

return [
    'routes' => [
        'pays' => [
            'route'         => '/pays',
            'controller'    => Controller\PaysController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::PARAMETRES_PAYS_VISUALISATION],
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'    => [
                    'route'      => '/saisie[/:pays]',
                    'controller' => Controller\PaysController::class,
                    'action'     => 'saisie',
                    'privileges' => [Privileges::PARAMETRES_PAYS_EDITION],
                ],
                'supprimer' => [
                    'route'      => '/supprimer/:pays',
                    'controller' => Controller\PaysController::class,
                    'action'     => 'supprimer',
                    'privileges' => [Privileges::PARAMETRES_PAYS_EDITION],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'nomenclatures' => [
                    'pages' => [
                        'pays' => [
                            'label'          => 'Pays',
                            'route'          => 'pays',
                            'order'          => 30,
                            'border - color' => '#111',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\PaysController::class => Controller\PaysControllerFactory::class,
    ],

    'forms' => [
        Form\PaysSaisieForm::class => Form\PaysSaisieFormFactory::class,
    ],

    'services' => [
        Service\PaysService::class => Service\PaysServiceFactory::class,
    ],
];
