<?php

namespace Application;

use UnicaenPrivilege\Guard\PrivilegeController;
use Application\Provider\Privilege\Privileges;

return [
    'router' => [
        'routes' => [
            'pays' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/pays',
                    'defaults' => [
                        'controller' => 'Application\Controller\Pays',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'modifier'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/saisie[/:pays]',
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/supprimer/:pays',
                            'defaults' => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'nomenclatures' => [
                                'pages' => [
                                    'gestion-pays' => [
                                        'label'          => 'Pays',
                                        'route'          => 'pays',
                                        'resource'       => PrivilegeController::getResourceId('Application\Controller\Pays', 'index'),
                                        'order'          => 30,
                                        'border - color' => '#111',
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
                    'controller' => 'Application\Controller\Pays',
                    'action'     => ['index'],
                    'privileges' => [Privileges::PARAMETRES_PAYS_VISUALISATION],
                ],
                [
                    'controller' => 'Application\Controller\Pays',
                    'action'     => ['saisie', 'supprimer'],
                    'privileges' => [Privileges::PARAMETRES_PAYS_EDITION],
                ],
            ],
        ],

    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\Pays' => Controller\Factory\PaysControllerFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            Form\Pays\PaysSaisieForm::class => Form\Pays\PaysSaisieFormFactory::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\PaysService::class => Service\PaysService::class,
        ],
    ],
];
