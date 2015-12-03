<?php

namespace Import;

use UnicaenAuth\Guard\PrivilegeController;
use Application\Provider\Privilege\Privileges;

return [
    'controllers'     => [
        'invokables' => [
            'Import\Controller\Import' => 'Import\Controller\ImportController',
        ],
    ],

    'router'          => [
        'routes' => [
            'import' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/import[/:action][/:table]',
                    'defaults' => [
                        '__NAMESPACE__' => 'Import\Controller',
                        'controller'    => 'Import',
                        'action'        => 'index',
                        'table'         => null,
                    ],
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'import' => [
                        'label'    => 'Import',
                        'order'    => 1,
                        'route'    => 'import',
                        'resource' => PrivilegeController::getResourceId('Import\Controller\Import','index'),
                        'pages'    => [
                            'admin'    => [
                                'label'    => "Tableau de bord principal",
                                'route'    => 'import',
                                'resource' => PrivilegeController::getResourceId('Import\Controller\Import','showimporttbl'),
                                'params'   => [
                                    'action' => 'showImportTbl',
                                ],
                                'visible'  => true,
                                'pages'    => [

                                ],
                            ],
                            'showDiff' => [
                                'label'    => "Écarts entre OSE et ses sources",
                                'route'    => 'import',
                                'resource' => PrivilegeController::getResourceId('Import\Controller\Import','showdiff'),
                                'params'   => [
                                    'action' => 'showDiff',
                                ],
                                'visible'  => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['index'],
                    'privileges' => [Privileges::IMPORT_ECARTS, Privileges::IMPORT_MAJ, Privileges::IMPORT_TBL, Privileges::IMPORT_VUES_PROCEDURES],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['showDiff'],
                    'privileges' => [Privileges::IMPORT_ECARTS],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['showImportTbl'],
                    'privileges' => [Privileges::IMPORT_TBL],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['update', 'updateTables'],
                    'privileges' => [Privileges::IMPORT_MAJ],
                ],
                [
                    'controller' => 'Import\Controller\Import',
                    'action'     => ['updateViewsAndPackages'],
                    'privileges' => [Privileges::IMPORT_VUES_PROCEDURES],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            'importServiceSchema'         => Service\Schema::class,
            'importServiceQueryGenerator' => Service\QueryGenerator::class,
            'importServiceIntervenant'    => Service\Intervenant::class,
            'importServiceDifferentiel'   => Service\Differentiel::class,
            'importProcessusImport'       => Processus\Import::class,
        ],
        'factories'  => [

        ],
    ],

    'view_helpers'    => [
        'invokables' => [
            'differentielListe' => View\Helper\DifferentielListe::class,
            'differentielLigne' => View\Helper\DifferentielLigne\DifferentielLigne::class,
        ],
    ],

    'view_manager'    => [
        'template_path_stack' => [
            'import' => __DIR__ . '/../view',
        ],
    ],
];