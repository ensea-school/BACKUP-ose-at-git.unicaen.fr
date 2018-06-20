<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'volume-horaire' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/volume-horaire',
                    'defaults' => [
                        'controller' => 'Application\Controller\VolumeHoraire',
                        'action'     => 'index',
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
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\VolumeHoraire' => Controller\VolumeHoraireController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\VolumeHoraireService::class            => Service\VolumeHoraireService::class,
            Service\VolumeHoraireEnsService::class         => Service\VolumeHoraireEnsService::class,
            Service\VolumeHoraireReferentielService::class => Service\VolumeHoraireReferentielService::class,
            Service\TypeVolumeHoraireService::class        => Service\TypeVolumeHoraireService::class,
            Service\EtatVolumeHoraireService::class        => Service\EtatVolumeHoraireService::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'volumeHoraireListe'           => View\Helper\VolumeHoraire\Liste::class,
            'volumeHoraireListeCalendaire' => View\Helper\VolumeHoraire\ListeCalendaire::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\VolumeHoraire\Saisie::class                 => Form\VolumeHoraire\Saisie::class,
            Form\VolumeHoraire\SaisieMultipleFieldset::class => Form\VolumeHoraire\SaisieMultipleFieldset::class, // Nécessite plusieurs instances
        ],
    ],
];
