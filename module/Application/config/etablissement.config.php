<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'etablissement' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/etablissement',
                    'defaults' => [
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'etablissement',
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
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Etablissement',
                    'action' => ['index', 'choisir', 'recherche', 'voir', 'apercevoir'],
                    'roles' => ['user']],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Etablissement'   => 'Application\Controller\EtablissementController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationEtablissement'       => 'Application\\Service\\Etablissement',
        ]
    ],
    'view_helpers' => [
        'invokables' => [
            'etablissementDl'   => 'Application\View\Helper\EtablissementDl',
            'etablissement'     => 'Application\View\Helper\EtablissementViewHelper',
        ],
    ],
];
