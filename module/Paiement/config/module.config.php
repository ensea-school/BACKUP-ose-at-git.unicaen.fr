<?php

namespace Paiement;

use Application\Provider\Privilege\Privileges;
use Paiement\Controller\TauxRemuController;
use Paiement\Service\TauxRemuService;
use Paiement\Service\TauxRemuServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'taux' => [
            'route'         => '/taux',
            'controller'    => TauxRemuController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'           => [
                    'route'      => '/saisir[/:tauxRemu]',
                    'controller' => TauxRemuController::class,
                    'action'     => 'saisir',
                ],
                'get'              => [
                    'route'      => '/get/:tauxRemu',
                    'controller' => TauxRemuController::class,
                    'action'     => 'get',
                ],
                'liste-taux'       => [
                    'route'      => '/liste-taux',
                    'controller' => TauxRemuController::class,
                    'action'     => 'getListeTaux',
                ],
                'supprimer'        => [
                    'route'      => '/supprimer/:tauxRemu',
                    'controller' => TauxRemuController::class,
                    'action'     => 'supprimer',
                ],
                'saisir-valeur'    => [
                    'route'      => '/saisir-valeur[/:tauxRemu][/:tauxRemuValeur]',
                    'controller' => TauxRemuController::class,
                    'action'     => 'saisirValeur',
                ],
                'supprimer-valeur' => [
                    'route'      => '/supprimer-valeur/:tauxRemuValeur',
                    'controller' => TauxRemuController::class,
                    'action'     => 'supprimerValeur',
                ],
            ],
        ],
    ],


    'navigation' => [
        'administration' => [
            'pages' => [
                'intervenants' => [
                    'pages' => [
                        'taux' => [
                            'label'    => "Taux de rémunération",
                            'route'    => 'taux',
                            'resource' => PrivilegeController::getResourceId(TauxRemuController::class, 'index'),
                            'order'    => 60,
                        ],
                    ],
                ],
            ],
        ],
    ],

    'rules' => [
    ],

    'guards' => [
        [
            'controller' => TauxRemuController::class,
            'action'     => ['index', 'get', 'getListeTaux'],
            'privileges' => [
                Privileges::TAUX_VISUALISATION,
            ],
        ],
        [
            'controller' => TauxRemuController::class,
            'action'     => ['saisir', 'saisirValeur'],
            'privileges' => [
                Privileges::TAUX_EDITION,
            ],
        ],
        [
            'controller' => TauxRemuController::class,
            'action'     => ['supprimer', 'supprimerValeur'],
            'privileges' => [
                Privileges::TAUX_SUPPRESSION,
            ],
        ],
    ],

    'controllers' => [
        TauxRemuController::class => Controller\TauxRemuControllerFactory::class,
    ],

    'services' => [
        TauxRemuService::class => TauxRemuServiceFactory::class,
    ],

    'forms' => [
        Form\TauxForm::class => Form\TauxFormFactory::class,
    ],

    'view_helpers' => [
    ],
];