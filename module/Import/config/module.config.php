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
                            'showDiff' => [
                                'label'    => "Écarts entre OSE et ses sources",
                                'description' => "Affiche, table par table, la liste des données différentes entre l'application et ses sources de données",
                                'route'    => 'import',
                                'resource' => PrivilegeController::getResourceId('Import\Controller\Import','showdiff'),
                                'params'   => [
                                    'action' => 'showDiff',
                                ],
                            ],
                            'updateTables' => [
                                'label'    => "Mise à jour des données à partir de leurs sources",
                                'description' => "Met à jour l'ensemble des données partir de leurs sources respectives.",
                                'route'    => 'import',
                                'resource' => PrivilegeController::getResourceId('Import\Controller\Import','updateTables'),
                                'params'   => [
                                    'action' => 'updateTables',
                                ],
                            ],
                            'show-import-tbl' => [
                                'label'    => "Tableau de bord principal",
                                'description' => "Liste, table par table, les colonnes dont les données sont importables ou non, leur caractéristiques et l'état de l'import à leur niveau.",
                                'route'    => 'import',
                                'resource' => PrivilegeController::getResourceId('Import\Controller\Import','show-import-tbl'),
                                'params'   => [
                                    'action' => 'show-import-tbl',
                                ],
                            ],
                            'updateViewsAndPackages'    => [
                                'label'    => "Mise à jour des vues différentielles et des procédures de mise à jour",
                                'description' => "Réactualise les vues différentielles d'import. Ces dernières servent à déterminer quelles données ont changé,
        sont apparues ou ont disparues des sources de données.
        Met également à jour les procédures de mise à jour qui actualisent les données de l'application à partir des informations
        fournies par les vues différentielles.
        Cette réactualisation n'est utile que si les vues sources ont été modifiées.",
                                'route'    => 'import',
                                'resource' => PrivilegeController::getResourceId('Import\Controller\Import','updateViewsAndPackages'),
                                'params'   => [
                                    'action' => 'updateViewsAndPackages',
                                ],
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
                    'action'     => ['show-import-tbl'],
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