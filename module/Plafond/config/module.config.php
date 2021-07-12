<?php

namespace Plafond;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

$config = [
    'module' => 'Plafond',

    'routes' => [
        'type'          => 'Literal',
        'options'       => [
            'route'    => '/plafond',
            'defaults' => [
                'controller' => 'Plafond\Controller\Plafond',
                'action'     => 'index',
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

    'navigation' => [
        'administration' => [
            'pages' => [
                'plafonds' => [
                    'icon'         => 'glyphicon glyphicon-wrench',
                    'label'        => "Plafonds",
                    'route'        => 'plafond',
                    'resource'     => PrivilegeController::getResourceId('Plafond\Controller\Plafond', 'index'),
                    'border-color' => '#9B9B9B',
                    'order'        => 120,
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['index'],
            'privileges' => Privileges::PLAFONDS_GESTION_VISUALISATION,
        ],
        [
            'controller' => 'Plafond\Controller\Plafond',
            'action'     => ['saisir', 'supprimer'],
            'privileges' => Privileges::PLAFONDS_GESTION_EDITION,
        ],
    ],

    'controllers' => [
        'Plafond\Controller\Plafond' => Controller\PlafondControllerFactory::class,
    ],

    'services' => [
        Service\PlafondApplicationService::class => Service\PlafondApplicationServiceFactory::class,
        Service\PlafondService::class            => Service\PlafondServiceFactory::class,
        Service\PlafondEtatService::class        => Service\PlafondEtatServiceFactory::class,
        Processus\PlafondProcessus::class        => Processus\PlafondProcessusFactory::class,
    ],

    'forms' => [
        Form\PlafondApplicationForm::class => Form\PlafondApplicationFormFactory::class,
    ],
];


return [
    'doctrine' => [
        'driver' => [
            'orm_default_driver' => [
                'paths' => [
                    __DIR__ . '/../src/' . $config['module'] . '/Entity/Db/Mapping',
                ],
            ],
            'orm_default'        => [
                'drivers' => [
                    $config['module'] . '\Entity\Db' => 'orm_default_driver',
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'router' => [
        'routes' => [
            strtolower($config['module']) => $config['routes'],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => $config['navigation'],
            ],
        ],
    ],

    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => $config['guards'],
        ],
    ],
    'controllers'     => [
        'factories' => $config['controllers'],
    ],
    'service_manager' => [
        'factories' => $config['services'],
    ],
    'form_elements'   => [
        'factories' => $config['forms'],
    ],
];