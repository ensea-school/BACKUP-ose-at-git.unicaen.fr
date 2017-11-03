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
            'ApplicationVolumeHoraire'            => Service\VolumeHoraire::class,
            'ApplicationVolumeHoraireEns'         => Service\VolumeHoraireEnsService::class,
            'ApplicationVolumeHoraireReferentiel' => Service\VolumeHoraireReferentiel::class,
            'ApplicationTypeVolumeHoraire'        => Service\TypeVolumeHoraire::class,
            'ApplicationEtatVolumeHoraire'        => Service\EtatVolumeHoraire::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'volumeHoraireListe' => View\Helper\VolumeHoraire\Liste::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'VolumeHoraireSaisie'                 => Form\VolumeHoraire\Saisie::class,
            'VolumeHoraireSaisieMultipleFieldset' => Form\VolumeHoraire\SaisieMultipleFieldset::class, // Nécessite plusieurs instances
        ],
    ],
];
