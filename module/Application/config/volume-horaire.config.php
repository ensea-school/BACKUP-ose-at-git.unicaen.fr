<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'volume-horaire'             => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/volume-horaire',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'VolumeHoraire',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'liste'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/liste[/:service]',
                            'constraints' => [
                                'service' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'liste',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/:service',
                            'constraints' => [
                                'service' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                ],
            ],
            'volume-horaire-referentiel' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/volume-horaire-referentiel',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'VolumeHoraireReferentiel',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'liste'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/liste[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/:serviceReferentiel',
                            'constraints' => [
                                'serviceReferentiel' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
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
                /* Enseignements */
                [
                    'controller' => 'Application\Controller\VolumeHoraire',
                    'action'     => ['liste'],
                    'privileges' => Privileges::ENSEIGNEMENT_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\VolumeHoraire',
                    'action'     => ['saisie'],
                    'privileges' => Privileges::ENSEIGNEMENT_EDITION,
                    'assertion'  => 'assertionService',
                ],

                /* Référentiel */
                [
                    'controller' => 'Application\Controller\VolumeHoraireReferentiel',
                    'action'     => ['liste'],
                    'privileges' => Privileges::REFERENTIEL_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\VolumeHoraireReferentiel',
                    'action'     => ['saisie'],
                    'privileges' => Privileges::REFERENTIEL_EDITION,
                    'assertion'  => 'assertionService',
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\VolumeHoraire'            => Controller\VolumeHoraireController::class,
            'Application\Controller\VolumeHoraireReferentiel' => Controller\VolumeHoraireReferentielController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationVolumeHoraire'                => Service\VolumeHoraire::class,
            'ApplicationVolumeHoraireReferentiel'     => Service\VolumeHoraireReferentiel::class,
            'ApplicationTypeVolumeHoraire'            => Service\TypeVolumeHoraire::class,
            'ApplicationEtatVolumeHoraire'            => Service\EtatVolumeHoraire::class,
            'FormVolumeHoraireSaisieMultipleHydrator' => Form\VolumeHoraire\SaisieMultipleHydrator::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'volumeHoraireListe'            => View\Helper\VolumeHoraire\Liste::class,
            'volumeHoraireReferentielListe' => View\Helper\VolumeHoraireReferentiel\Liste::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'VolumeHoraireSaisie'                            => Form\VolumeHoraire\Saisie::class,
            'VolumeHoraireSaisieMultipleFieldset'            => Form\VolumeHoraire\SaisieMultipleFieldset::class, // Nécessite plusieurs instances
            'VolumeHoraireReferentielSaisie'                 => Form\VolumeHoraireReferentiel\Saisie::class,
            'VolumeHoraireReferentielSaisieMultipleFieldset' => Form\VolumeHoraireReferentiel\SaisieMultipleFieldset::class, // Nécessite plusieurs instances
        ],
    ],
];
