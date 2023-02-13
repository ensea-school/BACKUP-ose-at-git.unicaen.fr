<?php

namespace Paiement;

use Application\Provider\Privilege\Privileges;
use Paiement\Controller\TauxController;
use Paiement\Service\TauxRemuService;
use Paiement\Service\TauxRemuServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'taux' => [
            'route'         => '/taux',
            'controller'    => TauxController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'           => [
                    'route'      => '/saisir[/:tauxRemu]',
                    'controller' => TauxController::class,
                    'action'     => 'saisir',
                ],
                'get'              => [
                    'route'      => '/get/:tauxRemu',
                    'controller' => TauxController::class,
                    'action'     => 'get',
                ],
                'liste-taux'       => [
                    'route'      => '/liste-taux',
                    'controller' => TauxController::class,
                    'action'     => 'getListeTaux',
                ],
                'supprimer'        => [
                    'route'      => '/supprimer/:tauxRemu',
                    'controller' => TauxController::class,
                    'action'     => 'supprimer',
                ],
                'saisir-valeur'    => [
                    'route'      => '/saisir-valeur[/:tauxRemu][/:tauxRemuValeur]',
                    'controller' => TauxController::class,
                    'action'     => 'saisirValeur',
                ],
                'supprimer-valeur' => [
                    'route'      => '/supprimer-valeur/:tauxRemuValeur',
                    'controller' => TauxController::class,
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
                            'resource' => PrivilegeController::getResourceId(TauxController::class, 'index'),
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
            'controller' => TauxController::class,
            'action'     => ['index', 'get', 'getListeTaux'],
            'privileges' => [
                Privileges::TAUX_VISUALISATION,
            ],
        ],
        [
            'controller' => TauxController::class,
            'action'     => ['saisir', 'saisirValeur'],
            'privileges' => [
                Privileges::TAUX_EDITION,
            ],
        ],
        [
            'controller' => TauxController::class,
            'action'     => ['supprimer', 'supprimerValeur'],
            'privileges' => [
                Privileges::TAUX_SUPPRESSION,
            ],
        ],
    ],

    'controllers' => [
        TauxController::class => Controller\TauxControllerFactory::class,
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