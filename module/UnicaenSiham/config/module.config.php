<?php

namespace UnicaenSiham;


use UnicaenSiham\Controller\Factory\IndexControllerFactory;
use UnicaenSiham\Controller\IndexController;
use UnicaenSiham\Service\Factory\SihamClientFactory;
use UnicaenSiham\Service\Factory\SihamFactory;
use UnicaenSiham\Service\Siham;
use UnicaenSiham\Service\SihamClient;


return [

    'router'          => [
        'routes' => [
            'siham' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/siham',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'voir' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/voir-agent/:matricule',
                            'defaults' => [
                                'action' => 'voir',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],

        ],
    ],
    'bjyauthorize'    => [
        'guards' => [
            \BjyAuthorize\Guard\Controller::class => [
                [
                    'controller' => IndexController::class,
                    'action'     => [
                        'index',
                        'voir',
                    ],
                    'roles'      => ['guest'],

                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            SihamClient::class => SihamClientFactory::class,
            Siham::class       => SihamFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
