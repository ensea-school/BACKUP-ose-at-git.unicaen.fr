<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'motif-modification-service' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/motif-modification-service',
                    'defaults' => [
                        'controller' => 'Application\Controller\MotifModificationService',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'delete' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:motifModificationServiceDu',
                            'constraints' => [
                                'motif-modification-service' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:motifModificationServiceDu]',
                            'constraints' => [
                                'motif-modification-service' => '[0-9]*',
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
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'motif-modification-service' => [
                                'label'        => 'Motifs de modification du service dû',
                                'icon'         => 'fas fa-graduation-cap',
                                'route'        => 'motif-modification-service',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\MotifModificationService', 'index'),
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
                    'controller' => 'Application\Controller\MotifModificationService',
                    'action'     => ['index'],
                    'privileges' => Privileges::MOTIFS_MODIFICATION_SERVICE_DU_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\MotifModificationService',
                    'action'     => ['saisie', 'delete'],
                    'privileges' => Privileges::MOTIFS_MODIFICATION_SERVICE_DU_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\MotifModificationService' => Controller\MotifModificationServiceController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\MotifModificationServiceService::class => Service\MotifModificationServiceService::class,
        ],
    ],
    'view_helpers'    => [
    ],
    'form_elements'   => [
        'invokables' => [
            Form\MotifModificationService\MotifModificationServiceSaisieForm::class => Form\MotifModificationService\MotifModificationServiceSaisieForm::class,
        ],
    ],
];
