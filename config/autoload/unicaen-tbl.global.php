<?php

namespace UnicaenTbl;

use UnicaenAuth\Guard\PrivilegeController;
use UnicaenTbl\Controller\AdminController;

return [
    'unicaen-tbl' => [
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'unicaen-tbl' => [
                                'label'    => 'Tableaux de bord',
                                'order'    => 1,
                                'route'    => 'unicaen-tbl',
                                'resource' => PrivilegeController::getResourceId(AdminController::class, 'index'),
                                /*'pages'    => [
                                    'showDiff'               => [
                                        'label'       => "Écarts entre les données de l'application et ses sources",
                                        'description' => "Affiche, table par table, la liste des données différentes entre l'application et ses sources de données",
                                        'route'       => 'import',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Import', 'show-diff'),
                                        'params'      => [
                                            'action' => 'show-diff',
                                        ],
                                    ],
                                ],*/
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];