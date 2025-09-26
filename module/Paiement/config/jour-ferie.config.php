<?php

namespace Paiement;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'jour-ferie' => [
            'route'         => '/jour-ferie',
            'controller'    => Controller\JourFerieController::class,
            'action'        => 'index',
            'privileges' => Privileges::JOUR_FERIE_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'delete' => [
                    'route'       => '/delete/:jourFerie',
                    'controller'  => Controller\JourFerieController::class,
                    'action'      => 'delete',
                    'constraints' => [
                        'jourFerie' => '[0-9]*',
                    ],
                    'privileges' => Privileges::JOUR_FERIE_EDITION,
                ],
                'saisie' => [
                    'route'       => '/saisie/[:jourFerie]',
                    'controller'  => Controller\JourFerieController::class,
                    'action'      => 'saisie',
                    'constraints' => [
                        'jourFerie' => '[0-9]*',
                    ],
                    'privileges' => Privileges::JOUR_FERIE_EDITION,
                ],
            ],
        ],

    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'finances' => [
                    'pages' => [
                        'jour-ferie' => [
                            'label'    => 'Jours fériés',
                            'route'    => 'jour-ferie',
                            'resource' => Authorize::controllerResource(Controller\JourFerieController::class, 'index'),
                            'order'    => 11,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\JourFerieController::class => Controller\JourFerieControllerFactory::class,
    ],

    'forms' => [
        Form\JourFerieForm::class => Form\JourFerieFormFactory::class,
    ],
];
