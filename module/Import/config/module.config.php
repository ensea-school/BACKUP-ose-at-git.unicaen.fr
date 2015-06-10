<?php

namespace Import;

use Application\Entity\Db\Privilege;

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
                        'order'    => 1,
                        'route'    => 'import',
                        'resource' => 'controller/Import\Controller\Import:index',
                        'pages' => [
                            'admin' => [
                                'label'  => "Tableau de bord principal",
                                'route'  => 'import',
                                'resource' => 'controller/Import\Controller\Import:showimporttbl',
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
                                'resource' => 'controller/Import\Controller\Import:showdiff',
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
            'Application\Guard\PrivilegeController' => [
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['index'],
                    'privileges' => [Privilege::IMPORT_ECARTS, Privilege::IMPORT_MAJ, Privilege::IMPORT_TBL, Privilege::IMPORT_VUES_PROCEDURES],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['showDiff'],
                    'privileges' => [Privilege::IMPORT_ECARTS],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['showImportTbl'],
                    'privileges' => [Privilege::IMPORT_TBL],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['update','updateTables'],
                    'privileges' => [Privilege::IMPORT_MAJ],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['updateViewsAndPackages'],
                    'privileges' => [Privilege::IMPORT_VUES_PROCEDURES],
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