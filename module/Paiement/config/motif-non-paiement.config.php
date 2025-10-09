<?php

namespace Paiement;

use Application\Provider\Privileges;

return [
    'routes' => [
        'motif-non-paiement' => [
            'route'         => '/motif-non-paiement',
            'controller'    => Controller\MotifNonPaiementController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'supprimer' => [
                    'route'       => '/supprimer/:motifNonPaiement',
                    'controller'  => Controller\MotifNonPaiementController::class,
                    'action'      => 'supprimer',
                    'constraints' => [
                        'motifNonPaiement' => '[0-9]*',
                    ],
                ],
                'saisir'    => [
                    'route'       => '/saisir/[:motifNonPaiement]',
                    'controller'  => Controller\MotifNonPaiementController::class,
                    'action'      => 'saisir',
                    'constraints' => [
                        'motifNonPaiement' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'rh' => [
                    'pages' => [
                        'motif-non-paiement' => [
                            'label'    => 'Motifs de non paiement',
                            'route'    => 'motif-non-paiement',
                            'order'    => 50,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\MotifNonPaiementController::class,
            'action'     => ['index'],
            'privileges' => Privileges::MOTIF_NON_PAIEMENT_ADMINISTRATION_VISUALISATION,
        ],
        [
            'controller' => Controller\MotifNonPaiementController::class,
            'action'     => ['saisir', 'supprimer'],
            'privileges' => Privileges::MOTIF_NON_PAIEMENT_ADMINISTRATION_EDITION,
        ],
    ],

    'controllers' => [
        Controller\MotifNonPaiementController::class => Controller\MotifNonPaiementControllerFactory::class,
    ],

    'services' => [
        Service\MotifNonPaiementService::class => Service\MotifNonPaiementServiceFactory::class,
    ],

    'view_helpers' => [
    ],

    'forms' => [
        Form\MotifNonPaiement\MotifNonPaiementSaisieForm::class => Form\MotifNonPaiement\MotifNonPaiementSaisieFormFactory::class,
    ],
];
