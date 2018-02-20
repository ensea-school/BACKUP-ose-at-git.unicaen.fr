<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [
    'unicaen-import' => [
        'differentiel_view_helpers' => [
            'CHEMIN_PEDAGOGIQUE'  => View\Helper\Import\CheminPedagogiqueViewHelper::class,
            'ELEMENT_PEDAGOGIQUE' => View\Helper\Import\ElementPedagogiqueViewHelper::class,
            'INTERVENANT'         => View\Helper\Import\IntervenantViewHelper::class,
            'ETAPE'               => View\Helper\Import\EtapeViewHelper::class,
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'import'  => ['visible' => false],
                    'administration' => [
                        'pages' => [
                            'import' => [
                                'label'    => 'Synchronisation',
                                'order'    => 1,
                                'route'    => 'import',
                                'resource' => PrivilegeController::getResourceId('Import\Controller\Import', 'index'),
                                'pages'    => [
                                    'differentiel'               => [
                                        'label'       => "Écarts entre les données de l'application et ses sources",
                                        'description' => "Affiche, table par table, la liste des données différentes entre l'application et ses sources de données",
                                        'route'       => 'import',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Import', 'differentiel'),
                                        'params'      => [
                                            'action' => 'index',
                                        ],
                                    ],
                                    'sources'                   => [
                                        'label'       => "Sources de données",
                                        'description' => "Liste des sources de données",
                                        'route'       => 'import',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Import', 'sources'),
                                        'params'      => [
                                            'action' => 'sources',
                                        ],
                                    ],
                                    'tableau-bord'        => [
                                        'label'       => "Tableau de bord principal",
                                        'description' => "Liste, table par table, les colonnes dont les données sont importables ou non, leur caractéristiques et l'état de l'import à leur niveau.",
                                        'route'       => 'import',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Import', 'tableau-bord'),
                                        'params'      => [
                                            'action' => 'tableau-bord',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];