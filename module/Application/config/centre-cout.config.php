<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'centre-cout' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/centre-cout',
                    'defaults' => [
                        'controller' => 'Application\Controller\CentreCout',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'delete'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete/:centreCout',
                            'constraints' => [
                                'centreCout' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete',
                            ],
                        ],
                    ],
                    'saisie'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie/[:centreCout]',
                            'constraints' => [
                                'centreCout' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'delete-structure' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/delete-structure/:centreCoutStructure',
                            'constraints' => [
                                'centreCoutStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'delete-structure',
                            ],
                        ],
                    ],
                    'saisie-structure' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie-structure/:centreCout/[:centreCoutStructure]',
                            'constraints' => [
                                'centreCout'          => '[0-9]*',
                                'centreCoutStructure' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie-structure',
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
                            'centre-cout' => [
                                'label'        => 'Centres de Coûts',
                                'icon'         => 'fa fa-graduation-cap',
                                'route'        => 'centre-cout',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\CentreCout', 'index'),
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
                    'controller' => 'Application\Controller\CentreCout',
                    'action'     => ['index'],
                    'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\CentreCout',
                    'action'     => ['saisie', 'delete', 'saisie-structure', 'delete-structure'],
                    'privileges' => Privileges::CENTRES_COUTS_ADMINISTRATION_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\CentreCout' => Controller\CentreCoutController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\CentreCoutService::class          => Service\CentreCoutService::class,
            Service\CentreCoutStructureService::class => Service\CentreCoutStructureService::class,
            Service\CcActiviteService::class          => Service\CcActiviteService::class,
        ],
    ],
    'view_helpers'    => [
    ],
    'form_elements'   => [
        'invokables' => [
            Form\CentreCout\CentreCoutSaisieForm::class          => Form\CentreCout\CentreCoutSaisieForm::class,
            Form\CentreCout\CentreCoutStructureSaisieForm::class => Form\CentreCout\CentreCoutStructureSaisieForm::class,
        ],
    ],
];
