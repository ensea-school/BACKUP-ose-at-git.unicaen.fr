<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

return [
    'unicaen-import' => [
        'differentiel_view_helpers' => [
            'CHEMIN_PEDAGOGIQUE'  => View\Helper\Import\CheminPedagogiqueViewHelper::class,
            'ELEMENT_PEDAGOGIQUE' => View\Helper\Import\ElementPedagogiqueViewHelper::class,
            'INTERVENANT'         => View\Helper\Import\IntervenantViewHelper::class,
            'PERSONNEL'           => View\Helper\Import\PersonnelViewHelper::class,
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
                                'label'    => 'Import',
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
                                    'tableau-bord'        => [
                                        'label'       => "Tableau de bord principal",
                                        'description' => "Liste, table par table, les colonnes dont les données sont importables ou non, leur caractéristiques et l'état de l'import à leur niveau.",
                                        'route'       => 'import',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Import', 'tableau-bord'),
                                        'params'      => [
                                            'action' => 'tableau-bord',
                                        ],
                                    ],
                                    'update-views-and-packages' => [
                                        'label'       => "Mise à jour des vues différentielles et des procédures de mise à jour",
                                        'description' => "Réactualise les vues différentielles d'import. Ces dernières servent à déterminer quelles données ont changé,
        sont apparues ou ont disparues des sources de données.
        Met également à jour les procédures de mise à jour qui actualisent les données de l'application à partir des informations
        fournies par les vues différentielles.
        Cette réactualisation n'est utile que si les vues sources ont été modifiées.",
                                        'route'       => 'import',
                                        'resource'    => PrivilegeController::getResourceId('Import\Controller\Import', 'update-views-and-packages'),
                                        'params'      => [
                                            'action' => 'update-views-and-packages',
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