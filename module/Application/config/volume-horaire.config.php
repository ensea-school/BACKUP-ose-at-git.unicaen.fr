<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'volume-horaire' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/volume-horaire',
                    'defaults' => [
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'VolumeHoraire',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/saisie/:service',
                            'constraints' => [
                                'service' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                ],
            ],
            'volume-horaire-referentiel' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/volume-horaire-referentiel',
                    'defaults' => [
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'VolumeHoraireReferentiel',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/saisie/:serviceReferentiel',
                            'constraints' => [
                                'serviceReferentiel' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\VolumeHoraire',
                    'action' => ['liste', 'saisie'],
                    'roles' => [R_INTERVENANT, R_COMPOSANTE, R_ADMINISTRATEUR]
                ],
                [
                    'controller' => 'Application\Controller\VolumeHoraireReferentiel',
                    'action' => ['liste', 'saisie'],
                    'roles' => [R_INTERVENANT, R_COMPOSANTE, R_ADMINISTRATEUR]
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\VolumeHoraire'            => Controller\VolumeHoraireController::class,
            'Application\Controller\VolumeHoraireReferentiel' => Controller\VolumeHoraireReferentielController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationVolumeHoraire'                  => Service\VolumeHoraire::class,
            'ApplicationVolumeHoraireReferentiel'       => Service\VolumeHoraireReferentiel::class,
            'ApplicationTypeVolumeHoraire'              => Service\TypeVolumeHoraire::class,
            'ApplicationEtatVolumeHoraire'              => Service\EtatVolumeHoraire::class,
            'FormVolumeHoraireSaisieMultipleHydrator'   => Form\VolumeHoraire\SaisieMultipleHydrator::class,
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'volumeHoraireListe'                        => View\Helper\VolumeHoraire\Liste::class,
            'volumeHoraireReferentielListe'             => View\Helper\VolumeHoraireReferentiel\Liste::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'VolumeHoraireSaisie'                            => Form\VolumeHoraire\Saisie::class,
            'VolumeHoraireSaisieMultipleFieldset'            => Form\VolumeHoraire\SaisieMultipleFieldset::class, // Nécessite plusieurs instances
            'VolumeHoraireReferentielSaisie'                 => Form\VolumeHoraireReferentiel\Saisie::class,
            'VolumeHoraireReferentielSaisieMultipleFieldset' => Form\VolumeHoraireReferentiel\SaisieMultipleFieldset::class, // Nécessite plusieurs instances
        ],
    ],
];
