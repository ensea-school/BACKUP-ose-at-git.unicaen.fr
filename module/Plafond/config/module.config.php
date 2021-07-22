<?php

namespace Plafond;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [

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
        Form\PlafondForm::class            => Form\PlafondFormFactory::class,
    ],
];