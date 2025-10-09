<?php

namespace Application;

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
        'js_ace_url'                => '/ext/ace-builds-master/src-min/ace.js',
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
                                        'order'       => 20,
                                    ],
                                    'tables'       => [
                                        'label'    => 'Tables',
                                        'route'    => 'import/tables',
                                        'order'    => 40,
                                    ],
                                    'tableau-bord' => [
                                        'label'       => "Tableau de bord principal",
                                        'description' => "Liste, table par table, les colonnes dont les données sont importables ou non, leur caractéristiques et l'état de l'import à leur niveau.",
                                        'route'       => 'import/tableau-bord',
                                        'order'       => 30,
                                    ],
                                    'differentiel' => [
                                        'label'       => "Écarts entre les données de l'application et ses sources",
                                        'description' => "Affiche, table par table, la liste des données différentes entre l'application et ses sources de données",
                                        'route'       => 'import/differentiel',
                                        'order'       => 10,
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