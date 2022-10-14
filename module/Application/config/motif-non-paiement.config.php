<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'motif-non-paiement' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/motif-non-paiement',
                    'defaults' => [
                        'controller' => 'Application\Controller\MotifNonPaiement',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'supprimer' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/supprimer/:motifNonPaiement',
                            'constraints' => [
                                'motifNonPaiement' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'saisir'    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisir/[:motifNonPaiement]',
                            'constraints' => [
                                'motifNonPaiement' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisir',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'rh' => [
                                'pages' => [
                                    'motif-non-paiement' => [
                                        'label'        => 'Motifs de non paiement',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'motif-non-paiement',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\MotifNonPaiement', 'index'),
                                        'order'        => 80,
                                        'border-color' => '#BBCF55',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\MotifNonPaiement',
                    'action'     => ['index'],
                    'privileges' => Privileges::MOTIF_NON_PAIEMENT_ADMINISTRATION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\MotifNonPaiement',
                    'action'     => ['saisir', 'supprimer'],
                    'privileges' => Privileges::MOTIF_NON_PAIEMENT_ADMINISTRATION_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\MotifNonPaiement' => Controller\MotifNonPaiementController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\MotifNonPaiementService::class => Service\MotifNonPaiementService::class,
        ],
    ],
    'view_helpers'    => [
    ],
    'form_elements'   => [
        'invokables' => [
            Form\MotifNonPaiement\Saisie::class => Form\MotifNonPaiement\MotifNonPaiementSaisieForm::class,
        ],
    ],
];
