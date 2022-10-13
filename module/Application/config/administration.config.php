<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

$rubriques = [
    'droits'          => [
        'icon'         => 'fas fa-power-off',
        'label'        => "Droits d'accès",
        'title'        => "Gestion des droits d'accès",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Droits', 'index'),
        'border-color' => '#bbcf55',
    ],
    'configuration'   => [
        'icon'         => 'fas fa-power-off',
        'label'        => "Configuration",
        'title'        => "Paramétrages de base de l'application",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
        'border-color' => '#f5e79e',
    ],
    'nomenclatures'   => [
        'icon'         => 'fas fa-power-off',
        'label'        => "Nomenclatures",
        'title'        => "Diverses nomenclatures en usage",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
        'border-color' => '#217dd8',
    ],
    'intervenants'    => [
        'icon'         => 'fas fa-power-off',
        'label'        => "Intervenants",
        'title'        => "Paramétrages liés aux intervenants",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
        'border-color' => '#E5272E',
    ],
    'odf'             => [
        'icon'         => 'fas fa-power-off',
        'label'        => "Offre de formation",
        'title'        => "Administration du fonctionnement de l'offre de formation",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
        'border-color' => '#71dfd7',
    ],
    'rh'              => [
        'icon'         => 'fas fa-power-off',
        'label'        => "RH",
        'title'        => "Nomenclatures et paramétrages relatifs aux ressources humaines",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
        'border-color' => '#9e9e9e',
    ],
    'finances'        => [
        'icon'         => 'fas fa-power-off',
        'label'        => "Finances",
        'title'        => "Nomenclatures et paramétrages liés aux aspects financiers",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
        'border-color' => '#eb4995',
    ],
    'synchronisation' => [
        'icon'         => 'fas fa-power-off',
        'label'        => "Synchronisation",
        'title'        => "Liaisons entre OSE et le système d'information",
        'resource'     => PrivilegeController::getResourceId('Import\Controller\Import', 'index'),
        'border-color' => '#9f491f',
        'route'        => 'import',
    ],
];

$config = [
    'router' => [
        'routes' => [
            'administration' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    // remplies automatiquement
                ],
            ],


            'administration-type'               => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration-type',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'administration-types',
                    ],
                ],
                'may_terminate' => true,
            ],
            'administration-referentiel-commun' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration-referentiel-commun',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'administration-referentiel-commun',
                    ],
                ],
                'may_terminate' => true,
            ],
            'administration-financiere'         => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration-financiere',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'administration-financiere',
                    ],
                ],
                'may_terminate' => true,
            ],
            'administration-intervenant'        => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration-intervenant',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'administration-intervenant',
                    ],
                ],
                'may_terminate' => true,
            ],
            'administration-nomenclature-rh'    => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration-nomenclature-rh',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'administration-nomenclature-rh',
                    ],
                ],
                'may_terminate' => true,
            ],
            'administration-periode'            => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration-periode',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'administration-periode',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'label'    => "Administration",
                        'route'    => 'administration',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
                        'order'    => 7,
                        'pages'    => [
                            // remplies automatiquement

                            'gestion-referentiel-commun' => [
                                'label'          => 'Gestion dictionnaires de données',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'administration-referentiel-commun',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Administration', 'administration-referentiel-commun'),
                                'order'          => 82,
                                'border - color' => '#111',
                                'pages'          => [
                                    'voirie' => [
                                        'label'        => 'Gestion des voiries',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'voirie',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Voirie', 'index'),
                                        'order'        => 800,
                                        'border-color' => '#BBCF55',
                                    ],

                                ],
                            ],
                            'gestion-nomenclature-rh'    => [
                                'label'          => 'Gestion des nomenclatures RH',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'administration-nomenclature-rh',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Administration', 'administration-nomenclature-rh'),
                                'order'          => 83,
                                'border - color' => '#111',
                                'pages'          => [
                                    'Employeurs' => [
                                        'border-color' => '#9F491F',
                                        'icon'         => 'fas fa-table-list',
                                        'label'        => "Gestion des employeurs",
                                        'title'        => "Gestion des employeurs",
                                        'route'        => 'employeur',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Employeur', 'index'),
                                        'order'        => 799,
                                    ],
                                    'grade'      => [
                                        'label'        => 'Gestion des grades',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'grades',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Grade', 'index'),
                                        'order'        => 800,
                                        'border-color' => '#BBCF55',
                                    ],
                                    'corps'      => [
                                        'label'        => 'Gestion des corps',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'corps',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Corps', 'index'),
                                        'order'        => 801,
                                        'border-color' => '#BBCF55',
                                    ],
                                ],
                            ],
                            'gestion-periode'            => [
                                'label'          => 'Gestion des périodes',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'periodes',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Periode', 'index'),
                                'order'          => 84,
                                'border - color' => '#111',
                            ],
                            'gestion-etablissement'      => [
                                'label'          => 'Gestion des établissements',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'etablissement',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Etablissement', 'index'),
                                'order'          => 85,
                                'border - color' => '#111',
                            ],
                            'gestion-type-formation'     => [
                                'label'          => 'Gestion des types de formations',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'type-formation',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\TypeFormation', 'index'),
                                'order'          => 86,
                                'border - color' => '#111',
                            ],
                            'gestion-pays'               => [
                                'label'          => 'Gestion des Pays',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'pays',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Pays', 'index'),
                                'order'          => 87,
                                'border - color' => '#111',
                            ],
                            'gestion-departement'        => [
                                'label'          => 'Gestion des Départements',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'departement',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Departement', 'index'),
                                'order'          => 88,
                                'border - color' => '#111',
                            ],
                            'gestion-intervenant'        => [
                                'label'          => 'Gestion intervenants (Statuts, PJ,  etc...)',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'administration-intervenant',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Administration', 'administration-intervenant'),
                                'order'          => 80,
                                'border - color' => '#111',
                                'pages'          => [
                                    'statut'                                    => [
                                        'label'        => 'Edition des statuts des intervenants',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'statut',
                                        'resource'     => PrivilegeController::getResourceId('Intervenant\Controller\Statut', 'index'),
                                        'order'        => 90,
                                        'border-color' => '#BBCF55',
                                    ],
                                    'type-piece-jointe-statut'                  => [
                                        'label'      => "Pièces justificatives attendues par statut d'intervenant",
                                        'title'      => "Permet de personnaliser les pièces justificatives à demander en fonction du statut des intervenants",
                                        'route'      => 'piece-jointe/type-piece-jointe-statut',
                                        'withtarget' => true,
                                        'order'      => 91,
                                        'resource'   => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'type-piece-jointe-statut'),
                                    ],
                                    'gestion-champs-autres-dossier-intervenant' => [
                                        'label'      => "Gestion des champs personnalisés pour le dossier intervenant",
                                        'title'      => "Editer et modifier les 5 champs personnalisables pour les dossiers des intervenant",
                                        'route'      => 'autres-infos',
                                        'withtarget' => true,
                                        'order'      => 92,
                                        'resource'   => PrivilegeController::getResourceId('Application\Controller\Autres', 'index'),
                                    ],
                                ],
                            ],
                            'administration-financiere'  => [
                                'label'          => 'Données financières',
                                'icon'           => 'fas fa-table-list',
                                'route'          => 'administration-financiere',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Administration', 'administration-financiere'),
                                'order'          => 81,
                                'border - color' => '#111',
                                'pages'          => [
                                    'centre-cout'          => [
                                        'label'        => 'Edition des centres de coûts',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'centre-cout',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\CentreCout', 'index'),
                                        'order'        => 80,
                                        'border-color' => '#BBCF55',
                                    ],
                                    'centre-cout-activite' => [
                                        'label'        => 'Edition des types d\'activités des centres de coûts',
                                        'icon'         => 'fas fa-graduation-cap',
                                        'route'        => 'centre-cout-activite',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\CentreCout', 'index'),
                                        'order'        => 80,
                                        'border-color' => '#BBCF55',
                                    ],

                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Administration',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::IMPORT_ECARTS,
                        Privileges::IMPORT_MAJ,
                        Privileges::IMPORT_TBL,
                        Privileges::IMPORT_VUES_PROCEDURES,
                        Privileges::IMPORT_TABLES_VISUALISATION,
                        Privileges::IMPORT_SOURCES_VISUALISATION,
                        Privileges::WORKFLOW_DEPENDANCES_VISUALISATION,
                        Privileges::DISCIPLINE_GESTION,
                        Privileges::DROIT_ROLE_VISUALISATION,
                        Privileges::DROIT_PRIVILEGE_VISUALISATION,
                        Privileges::DROIT_AFFECTATION_VISUALISATION,
                        Privileges::PARAMETRES_GENERAL_VISUALISATION,
                        Privileges::REFERENTIEL_ADMIN_VISUALISATION,
                        Privileges::REFERENTIEL_COMMUN_VOIRIE_VISUALISATION,
                        Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION,
                        Privileges::NOMENCLATURE_RH_GRADES_VISUALISATION,
                        Privileges::NOMENCLATURE_RH_CORPS_VISUALISATION,
                        Privileges::TYPE_INTERVENTION_VISUALISATION,
                        Privileges::TYPE_RESSOURCE_VISUALISATION,
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                        Privileges::PLAFONDS_VISUALISATION,
                        Privileges::CENTRES_COUTS_ADMINISTRATION_VISUALISATION,
                        Privileges::ETAT_SORTIE_ADMINISTRATION_VISUALISATION,
                        Privileges::TYPE_INTERVENTION_VISUALISATION,
                        Privileges::MODULATEUR_VISUALISATION,
                        Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_VISUALISATION,
                        Privileges::MOTIFS_MODIFICATION_SERVICE_DU_VISUALISATION,
                        Privileges::MOTIF_NON_PAIEMENT_ADMINISTRATION_VISUALISATION,
                        Privileges::ODF_RECONDUCTION_OFFRE,
                        Privileges::ODF_TYPE_FORMATION_VISUALISATION,
                        Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION,
                        Privileges::PARAMETRES_PERIODES_VISUALISATION,
                        Privileges::PARAMETRES_PAYS_VISUALISATION,
                        Privileges::PARAMETRES_DEPARTEMENT_VISUALISATION,
                        Privileges::INTERVENANT_STATUT_VISUALISATION,
                    ],
                    'assertion'  => Assertion\GestionAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Administration',
                    'action'     => ['rubrique'],
                    'roles'      => 'user',
                ],
            ],
        ],
    ],
    'controllers'  => [
        'factories' => [
            'Application\Controller\Administration' => Controller\Factory\AdministrationControllerFactory::class,
        ],
    ],
];

$order = 1;
foreach ($rubriques as $route => $rubrique) {
    $hasRoute          = array_key_exists('route', $rubrique);
    $rubrique['order'] = $order++;
    if (!$hasRoute) {
        $rubrique['route'] = 'administration/' . $route;
    }

    $config['navigation']['default']['home']['pages']['administration']['pages'][$route] = $rubrique;

    if (!$hasRoute) {
        $config['router']['routes']['administration']['child_routes'][$route] = [
            'type'          => 'Literal',
            'options'       => [
                'route'    => '/' . $route,
                'defaults' => [
                    'controller' => 'Application\Controller\Administration',
                    'action'     => 'rubrique',
                ],
            ],
            'may_terminate' => true,
        ];
    }
}

return $config;