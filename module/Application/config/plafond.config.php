<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'     => [
        'routes' => [
            'plafond' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/plafond',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Plafond',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'saisir'    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisir[/:plafondApplication]',
                            'constraints' => [
                                'plafondApplication' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisir',
                            ],
                        ],
                    ],
                    'supprimer' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/supprimer/:plafondApplication',
                            'constraints' => [
                                'plafondApplication' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    /* Menu */
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'plafonds' => [
                                'icon'         => 'glyphicon glyphicon-wrench',
                                'label'        => "Plafonds",
                                'route'        => 'plafond',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Plafond', 'index'),
                                'border-color' => '#9B9B9B',
                                'order'        => 120,
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
                    'controller' => 'Application\Controller\Plafond',
                    'action'     => ['index'],
                    'privileges' => Privileges::PLAFONDS_GESTION_VISUALISATION,
                ],
                [
                    'controller' => 'Application\Controller\Plafond',
                    'action'     => ['saisir','supprimer'],
                    'privileges' => Privileges::PLAFONDS_GESTION_EDITION,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\Plafond' => Controller\Factory\PlafondControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\PlafondApplicationService::class => Service\Factory\PlafondApplicationServiceFactory::class,
            Service\PlafondService::class => Service\Factory\PlafondServiceFactory::class,
            Service\PlafondEtatService::class => Service\Factory\PlafondEtatServiceFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\Plafond\PlafondApplicationForm::class => Form\Plafond\Factory\PlafondApplicationFormFactory::class,
        ],
    ],
];
