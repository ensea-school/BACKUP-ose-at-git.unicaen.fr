<?php

namespace Import;

return [
    'controllers' => [
        'invokables' => [
            'Import\Controller\Import'      => 'Import\Controller\ImportController',
        ],
    ],

    'router' => [
        'routes' => [
            'import' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/import[/:action][/:table]',
                    'defaults' => [
                        '__NAMESPACE__' => 'Import\Controller',
                        'controller' => 'Import',
                        'action'     => 'index',
                        'table'      => null
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'import' => [
                        'label'    => 'Import',
                        'route'    => 'import',
                        'resource' => 'controller/Import\Controller\Import:index',
                        'pages' => [
                            'admin' => [
                                'label'  => "Tableau de bord principal",
                                'route'  => 'import',
                                'params' => [
                                    'action' => 'showImportTbl',
                                ],
                                'visible' => true,
                                'pages' => [

                                ],
                            ],
                            'showDiff' => [
                                'label'  => "Écarts entre OSE et ses sources",
                                'route'  => 'import',
                                'params' => [
                                    'action' => 'showDiff',
                                ],
                                'visible' => true,
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
                    'controller' => 'Import\Controller\Import',
                    'action' => ['index','updateViewsAndPackages','showImportTbl','showDiff','update','updateTables'],
                    'roles' => ['administrateur'],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'import' => __DIR__ . '/../view',
        ],
    ],
];