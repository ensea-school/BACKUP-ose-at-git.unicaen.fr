<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'gestion' => [
                'type'    => 'Literal',
                'options' => [
                    'route' => '/gestion',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'gestion',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'annee' => [
                        'type'    => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/annee',
                            'defaults' => [
                                'action' => 'annee',
                                'controller' => 'Gestion',
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
                    'gestion' => [
                        'label'  => "Gestion",
                        'route'  => 'gestion',
                        'resource' => 'controller/Application\Controller\Index:gestion',
                        'pages' => [
                            'annee' => [
                                'label'    => "Année universitaire",
                                'title'    => "Configuration de l'année universitaire courante",
                                'route'    => 'gestion/annee',
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
                    'controller' => 'Application\Controller\Index',
                    'action'     => ['gestion'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                ],
                [
                    'controller' => 'Application\Controller\Gestion',
                    'action'     => ['annee'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Gestion'   => 'Application\Controller\GestionController',
        ],
    ],
];