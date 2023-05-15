<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;


$rubriques = [
    'droits'          => [
        'icon'         => 'fas fa-lock-open',
        'label'        => "Droits d'accès",
        'title'        => "Gestion des droits d'accès",
        'resource'     => PrivilegeController::getResourceId('Application\Controller\Droits', 'index'),
        'color' => '#bbcf55',
    ],
    'configuration'   => [
        'icon'         => 'fas fa-gear',
        'label'        => "Configuration",
        'title'        => "Paramétrages de base de l'application",
        'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
        'color' => '#f5e79e',
    ],
    'nomenclatures'   => [
        'icon'         => 'fas fa-folder-open',
        'label'        => "Nomenclatures",
        'title'        => "Diverses nomenclatures en usage",
        'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
        'color' => '#217dd8',
    ],
    'intervenants'    => [
        'icon'         => 'fas fa-user',
        'label'        => "Intervenants",
        'title'        => "Paramétrages liés aux intervenants",
        'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
        'color' => '#E5272E',
    ],
    'odf'             => [
        'icon'         => 'fas fa-user-graduate',
        'label'        => "Offre de formation",
        'title'        => "Administration du fonctionnement de l'offre de formation",
        'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
        'color' => '#71dfd7',
    ],
    'rh'              => [
        'icon'         => 'fas fa-paperclip',
        'label'        => "RH",
        'title'        => "Nomenclatures et paramétrages relatifs aux ressources humaines",
        'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
        'color' => '#9e9e9e',
    ],
    'finances'        => [
        'icon'         => 'fas fa-chart-line',
        'label'        => "Finances",
        'title'        => "Nomenclatures et paramétrages liés aux aspects financiers",
        'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
        'color' => '#eb4995',
    ],
    'synchronisation' => [
        'icon'         => 'fas fa-database',
        'label'        => "Synchronisation",
        'title'        => "Liaisons entre OSE et le système d'information",
        'resource'     => PrivilegeController::getResourceId('Import\Controller\Import', 'index'),
        'color' => '#9f491f',
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
                        'controller' => Controller\AdministrationController::class,
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
                        'controller' => Controller\AdministrationController::class,
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
                        'controller' => Controller\AdministrationController::class,
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
                        'controller' => Controller\AdministrationController::class,
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
                        'controller' => Controller\AdministrationController::class,
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
                        'controller' => Controller\AdministrationController::class,
                        'action'     => 'administration-nomenclature-rh',
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
                        'resource' => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
                        'order'    => 7,
                        'pages'    => [
                            // remplies automatiquement
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
                    'controller' => Controller\AdministrationController::class,
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
                    'controller' => Controller\AdministrationController::class,
                    'action'     => ['rubrique'],
                    'roles'      => 'user',
                ],
            ],
        ],
    ],
    'controllers'  => [
        'factories' => [
            Controller\AdministrationController::class => Controller\Factory\AdministrationControllerFactory::class,
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
                    'controller' => Controller\AdministrationController::class,
                    'action'     => 'rubrique',
                ],
            ],
            'may_terminate' => true,
        ];
    }
}

return $config;