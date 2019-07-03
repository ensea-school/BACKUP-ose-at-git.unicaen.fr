<?php

namespace Application;

return [
    'router'          => [
        'routes' => [
            'structure' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/structure',
                    'defaults' => [
                        'controller'    => 'Application\Controller\Structure',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'voir'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/voir/:structure',
                            'constraints' => [
                                'structure'     => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'bjyauthorize'    => [
        'guards'             => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Structure',
                    'action'     => ['voir'],
                    'roles'      => ['user'],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Structure' => [],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Structure' => Controller\StructureController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\StructureService::class     => Service\StructureService::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'structure' => View\Helper\StructureViewHelper::class,
        ],
    ],
];
