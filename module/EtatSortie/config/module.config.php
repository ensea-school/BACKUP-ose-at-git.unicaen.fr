<?php

namespace EtatSortie;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'etat-sortie' => [
            'route'         => '/etat-sortie',
            'controller'    => Controller\EtatSortieController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'telecharger' => [
                    'route'       => '/telecharger/:etatSortie',
                    'constraints' => [
                        'etatSortie' => '[0-9]*',
                    ],
                    'controller'  => Controller\EtatSortieController::class,
                    'action'      => 'telecharger',
                ],
                'saisir'      => [
                    'route'       => '/saisir[/:etatSortie]',
                    'constraints' => [
                        'etatSortie' => '[0-9]*',
                    ],
                    'controller'  => Controller\EtatSortieController::class,
                    'action'      => 'saisir',
                ],
                'supprimer'   => [
                    'route'       => '/supprimer/:etatSortie',
                    'constraints' => [
                        'etatSortie' => '[0-9]*',
                    ],
                    'controller'  => Controller\EtatSortieController::class,
                    'action'      => 'supprimer',
                ],
                'generer-pdf' => [
                    'route'       => '/generer/pdf/:etatSortie',
                    'constraints' => [
                        'etatSortie' => '[0-9]*',
                    ],
                    'controller'  => Controller\EtatSortieController::class,
                    'action'      => 'generer-pdf',
                ],
                'generer-csv' => [
                    'route'       => '/generer/csv/:etatSortie',
                    'constraints' => [
                        'etatSortie' => '[0-9]*',
                    ],
                    'controller'  => Controller\EtatSortieController::class,
                    'action'      => 'generer-csv',
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'configuration' => [
                    'pages' => [
                        'etat-sortie' => [
                            'color'    => '#9F491F',
                            'label'    => "États de sortie",
                            'title'    => "États de sortie",
                            'route'    => 'etat-sortie',
                            'resource' => Authorize::controllerResource(Controller\EtatSortieController::class, 'index'),
                            'order'    => 30,
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\EtatSortieController::class,
            'action'     => ['index', 'telecharger', 'generer-pdf', 'generer-csv'],
            'privileges' => [
                Privileges::ETAT_SORTIE_ADMINISTRATION_VISUALISATION,
            ],
        ],
        [
            'controller' => Controller\EtatSortieController::class,
            'action'     => ['saisir', 'supprimer'],
            'privileges' => [
                Privileges::ETAT_SORTIE_ADMINISTRATION_EDITION,
            ],
        ],
    ],

    'controllers' => [
        Controller\EtatSortieController::class => Controller\EtatSortieControllerFactory::class,
    ],

    'services' => [
        Service\EtatSortieService::class => Service\EtatSortieServiceFactory::class,
    ],

    'forms' => [
        Form\EtatSortieForm::class => Form\EtatSortieFormFactory::class,
    ],
];