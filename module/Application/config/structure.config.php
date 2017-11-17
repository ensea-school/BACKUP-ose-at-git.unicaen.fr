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
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Structure',
                        'action'        => 'index',
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
                    'recherche' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/recherche[/:term]',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],
                    'default'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'index',
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
                    'structure' => [
                        'label'   => 'Structures',
                        'title'   => "Gestion des structures",
                        'route'   => 'structure',
                        'visible' => false,
                        'params'  => [
                            'action' => 'index',
                        ],
                        'pages'   => [
                            'voir' => [
                                'label'   => "Voir",
                                'title'   => "Voir une structure",
                                'route'   => 'structure',
                                'visible' => false,
                                'pages'   => [],
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
                    'action'     => ['voir', 'apercevoir'],
                    'roles'      => ['user'],
                ],
                [
                    'controller' => 'Application\Controller\Structure',
                    'action'     => ['index', 'choisir', 'recherche'],
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
            'ApplicationPersonnel'          => Service\Personnel::class,
            Service\StructureService::class => Service\StructureService::class,
            'ApplicationTypeStructure'      => Service\TypeStructure::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'structure' => View\Helper\StructureViewHelper::class,
        ],
    ],
];
