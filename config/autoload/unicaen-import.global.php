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
        'entity_source_injector'    => [
            // Code unique de la source à injecter (null pour désactiver le mécanisme).
            'source_code' => 'OSE',
        ],
        'js_ace_url'                => '/vendor/ace-builds-master/src-min/ace.js',
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'import'         => ['visible' => false],
                    'administration' => [
                        'pages' => [
                            'synchronisation' => [
                                'pages' => [
                                    'sources'      => [
                                        'label'       => "Sources de données",
                                        'description' => "Liste des sources de données",
                                        'route'       => 'import/sources',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Source', 'index'),
                                    ],
                                    'tables'       => [
                                        'label'    => 'Tables',
                                        'route'    => 'import/tables',
                                        'resource' => PrivilegeController::getResourceId('Import\Controller\Table', 'index'),
                                    ],
                                    'tableau-bord' => [
                                        'label'       => "Tableau de bord principal",
                                        'description' => "Liste, table par table, les colonnes dont les données sont importables ou non, leur caractéristiques et l'état de l'import à leur niveau.",
                                        'route'       => 'import/tableau-bord',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Import', 'tableau-bord'),
                                    ],
                                    'differentiel' => [
                                        'label'       => "Écarts entre les données de l'application et ses sources",
                                        'description' => "Affiche, table par table, la liste des données différentes entre l'application et ses sources de données",
                                        'route'       => 'import/differentiel',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Differentiel', 'index'),
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