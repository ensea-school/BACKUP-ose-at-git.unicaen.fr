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
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'volume-horaire' => [
                        'label'    => 'Volumes horaires',
                        'title'    => "Gestion des volumes horaires",
                        'visible' => false,
                        'route'    => 'volume-horaire',
                        'params' => [
                            'action' => 'index',
                        ],
                        'pages' => [
                            'consultation' => [
                                'label'  => "Consultation",
                                'title'  => "Consultation des volumes horaires",
                                'route'  => 'volume-horaire',
                                'visible' => false,
                                'withtarget' => true,
                                'pages' => [],
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
                    'action' => ['voir', 'liste', 'saisie'],
                    'roles' => [R_INTERVENANT, R_COMPOSANTE, R_ADMINISTRATEUR]
                ],
                [
                    'controller' => 'Application\Controller\VolumeHoraireReferentiel',
                    'action' => ['voir', 'liste', 'saisie'],
                    'roles' => [R_INTERVENANT, R_COMPOSANTE, R_ADMINISTRATEUR]
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\VolumeHoraire'            => 'Application\Controller\VolumeHoraireController',
            'Application\Controller\VolumeHoraireReferentiel' => 'Application\Controller\VolumeHoraireReferentielController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationVolumeHoraire'                  => 'Application\\Service\\VolumeHoraire',
            'ApplicationVolumeHoraireReferentiel'       => 'Application\\Service\\VolumeHoraireReferentiel',
            'ApplicationTypeVolumeHoraire'              => 'Application\\Service\\TypeVolumeHoraire',
            'ApplicationEtatVolumeHoraire'              => 'Application\\Service\\EtatVolumeHoraire',
            'FormVolumeHoraireSaisieMultipleHydrator'   => 'Application\Form\VolumeHoraire\SaisieMultipleHydrator',
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'volumeHoraireDl'                           => 'Application\View\Helper\VolumeHoraire\Dl',
            'volumeHoraireListe'                        => 'Application\View\Helper\VolumeHoraire\Liste',
            'volumeHoraireReferentielDl'                => 'Application\View\Helper\VolumeHoraireReferentiel\Dl',
            'volumeHoraireReferentielListe'             => 'Application\View\Helper\VolumeHoraireReferentiel\Liste',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'VolumeHoraireSaisie'                            => 'Application\Form\VolumeHoraire\Saisie',
            'VolumeHoraireSaisieMultipleFieldset'            => 'Application\Form\VolumeHoraire\SaisieMultipleFieldset', // Nécessite plusieurs instances
            'VolumeHoraireReferentielSaisie'                 => 'Application\Form\VolumeHoraireReferentiel\Saisie',
            'VolumeHoraireReferentielSaisieMultipleFieldset' => 'Application\Form\VolumeHoraireReferentiel\SaisieMultipleFieldset', // Nécessite plusieurs instances
        ],
    ],
];
