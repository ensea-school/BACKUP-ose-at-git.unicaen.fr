<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'groupe-type-formation' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/groupe-type-formation',
                    'defaults' => [
                        'controller' => 'Application\Controller\GroupeTypeFormation',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'delete'                => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:groupe-type-formation',
                            'constraints' => [
                                'groupe-type-formation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'saisie'                => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:groupe-type-formation]',
                            'constraints' => [
                                'groupe-type-formation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'type-formation-delete' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/type-formation-delete/:type-formation',
                            'constraints' => [
                                'type-formation' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-formation-delete',
                            ],
                        ],
                    ],
                    'type-formation-saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/type-formation-saisie/:groupe-type-formation[/:type-formation]',
                            'constraints' => [
                                'groupe-type-formation' => '[0-9]*',
                                'type-formation'        => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'type-formation-saisie',
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
                            'groupe-type-formation' => [
                                'label'        => 'Diplômes',
                                'icon'         => 'fa fa-graduation-cap',
                                'route'        => 'groupe-type-formation',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\GroupeTypeFormation', 'index'),
                                'order'        => 80,
                                'border-color' => '#BBCF55',
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
                    'controller' => 'Application\Controller\GroupeTypeFormation',
                    'action'     => ['index'],
                    'privileges' => Privileges::DROIT_PRIVILEGE_EDITION,
                ],
                [
                    'controller' => 'Application\Controller\GroupeTypeFormation',
                    'action'     => ['saisie', 'delete', 'type-formation-saisie', 'type-formation-delete'],
                    'privileges' => Privileges::DROIT_PRIVILEGE_VISUALISATION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\GroupeTypeFormation' => Controller\GroupeTypeFormationController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\GroupeTypeFormationService::class => Service\GroupeTypeFormationService::class,
            Service\GroupeTypeFormationService::class => Service\TypeFormationService::class,
        ],
    ],
    'view_helpers'    => [
    ],
    'form_elements'   => [
        'invokables' => [
            Form\GroupeTypeFormation\TypeFormationSaisieForm::class     => Form\GroupeTypeFormation\TypeFormationSaisieForm::class,
            Form\GroupeTypeFormation\GroupeTypeFormationSaisieForm::class => Form\GroupeTypeFormation\GroupeTypeFormationSaisieForm::class,
        ],
    ],
];
