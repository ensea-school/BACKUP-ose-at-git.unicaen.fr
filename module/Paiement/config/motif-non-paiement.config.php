<?php

namespace Paiement;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

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
                    'action'      => 'supprimer',
                    'constraints' => [
                        'motifNonPaiement' => '[0-9]*',
                    ],
                ],
                'saisir'    => [
                    'route'       => '/saisir/[:motifNonPaiement]',
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
                            'resource' => PrivilegeController::getResourceId(Controller\MotifNonPaiementController::class, 'index'),
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
        'invokables' => [
            Controller\MotifNonPaiementController::class => Controller\MotifNonPaiementController::class,
        ],
    ],

    'services' => [
        'invokables' => [
            \Paiement\Service\MotifNonPaiementService::class => \Paiement\Service\MotifNonPaiementService::class,
        ],
    ],

    'view_helpers' => [
    ],

    'forms' => [
        'invokables' => [
            Form\MotifNonPaiement\Saisie::class => \Paiement\Form\MotifNonPaiement\MotifNonPaiementSaisieForm::class,
        ],
    ],
];
