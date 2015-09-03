<?php

namespace Application;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;

return [
    'router' => [
        'routes' => [
            'structure' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/structure',
                    'defaults' => [
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Structure',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'modifier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
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
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'structure' => [
                        'label'    => 'Structures',
                        'title'    => "Gestion des structures",
                        'route'    => 'structure',
                        'visible'  => false,
                        'params' => [
                            'action' => 'index',
                        ],
                        'pages' => [
                            'voir' => [
                                'label'  => "Voir",
                                'title'  => "Voir une structure",
                                'route'  => 'structure',
                                'visible' => false,
                                'withtarget' => true,
                                'pages' => [],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Structure',
                    'action' => ['voir', 'apercevoir'],
                    'roles' => [R_ROLE]
                ],
                [
                    'controller' => 'Application\Controller\Structure',
                    'action' => ['index', 'choisir', 'recherche'],
                    'roles' => [IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID]
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Structure'   => 'Application\Controller\StructureController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationPersonnel'       => 'Application\\Service\\Personnel',
            'ApplicationStructure'       => 'Application\\Service\\Structure',
            'ApplicationTypeStructure'   => 'Application\\Service\\TypeStructure',
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'structure'         => 'Application\View\Helper\StructureViewHelper',
        ],
    ],
];
