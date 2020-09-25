<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'administration'                    => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
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
                            'gestion-referentiel-commun' => [
                                'label'          => 'Gestion référentiels communs',
                                'icon'           => 'glyphicon glyphicon - list-alt',
                                'route'          => 'administration-referentiel-commun',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Administration', 'administration-referentiel-commun'),
                                'order'          => 80,
                                'border - color' => '#111',
                                'pages'          => [
                                    'voirie'     => [
                                        'label'        => 'Gestion des voiries',
                                        'icon'         => 'fa fa-graduation-cap',
                                        'route'        => 'voirie',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Voirie', 'index'),
                                        'order'        => 800,
                                        'border-color' => '#BBCF55',
                                    ],
                                    'Employeurs' => [
                                        'border-color' => '#9F491F',
                                        'icon'         => 'glyphicon glyphicon-list-alt',
                                        'label'        => "Gestion des employeurs",
                                        'title'        => "Gestion des employeurs",
                                        'route'        => 'employeur',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Employeur', 'index'),
                                        'order'        => 70,
                                    ],
                                ],
                            ],
                            'gestion-intervenant'        => [
                                'label'          => 'Gestion intervenants (Statuts, PJ,  etc...)',
                                'icon'           => 'glyphicon glyphicon - list-alt',
                                'route'          => 'administration-intervenant',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Administration', 'administration-intervenant'),
                                'order'          => 80,
                                'border - color' => '#111',
                                'pages'          => [
                                    'statut-intervenant'                         => [
                                        'label'        => 'Edition des statuts des intervenants',
                                        'icon'         => 'fa fa-graduation-cap',
                                        'route'        => 'statut-intervenant',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\StatutIntervenant', 'index'),
                                        'order'        => 90,
                                        'border-color' => '#BBCF55',
                                    ],
                                    'type-piece-jointe-statut'                   => [
                                        'label'      => "Pièces justificatives attendues par statut d'intervenant",
                                        'title'      => "Permet de personnaliser les pièces justificatives à demander en fonction du statut des intervenants",
                                        'route'      => 'piece-jointe/type-piece-jointe-statut',
                                        'withtarget' => true,
                                        'resource'   => PrivilegeController::getResourceId('Application\Controller\PieceJointe', 'type-piece-jointe-statut'),
                                    ],
                                    'gestion-champs-autres-dossier-intervenant'  => [
                                        'label'      => "Gestion des champs personnalisés pour le dossier intervenant",
                                        'title'      => "Editer et modifier les 5 champs personnalisables pour les dossiers des intervenant",
                                        'route'      => 'autres-infos',
                                        'withtarget' => true,
                                        'resource'   => PrivilegeController::getResourceId('Application\Controller\Autres', 'index'),
                                    ],
                                    'validation-volume-horaire-type-intervenant' => [
                                        'label'      => "Règles de validation par type d'intervenant",
                                        'title'      => "Permet de définir les priorités de validation de volumes horaires par type d'intervenant",
                                        'route'      => 'validation-volume-horaire-type-intervenant',
                                        'withtarget' => true,
                                        'resource'   => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'validation-volume-horaire-type-intervenant'),
                                    ],
                                ],
                            ],
                            'administration-financiere'  => [
                                'label'          => 'Données financières',
                                'icon'           => 'glyphicon glyphicon - list-alt',
                                'route'          => 'administration-financiere',
                                'resource'       => PrivilegeController::getResourceId('Application\Controller\Administration', 'administration-financiere'),
                                'order'          => 80,
                                'border - color' => '#111',
                                'pages'          => [
                                    'centre-cout'          => [
                                        'label'        => 'Edition des centres de coûts',
                                        'icon'         => 'fa fa-graduation-cap',
                                        'route'        => 'centre-cout',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\CentreCout', 'index'),
                                        'order'        => 80,
                                        'border-color' => '#BBCF55',
                                    ],
                                    'centre-cout-activite' => [
                                        'label'        => 'Edition des types d\'activités des centres de coûts',
                                        'icon'         => 'fa fa-graduation-cap',
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
                    'action'     => ['index', 'administration-types', 'administration-financiere', 'administration-intervenant', 'administration-referentiel-commun'],
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
                        Privileges::TYPE_INTERVENTION_VISUALISATION,
                        Privileges::TYPE_RESSOURCE_VISUALISATION,
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                        Privileges::PLAFONDS_GESTION_VISUALISATION,
                        Privileges::CENTRES_COUTS_ADMINISTRATION_VISUALISATION,
                        Privileges::ETAT_SORTIE_ADMINISTRATION_VISUALISATION,
                        Privileges::TYPE_INTERVENTION_VISUALISATION,
                        Privileges::MODULATEUR_VISUALISATION,
                        Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_VISUALISATION,
                        Privileges::MOTIFS_MODIFICATION_SERVICE_DU_VISUALISATION,
                        Privileges::MOTIF_NON_PAIEMENT_VISUALISATION,
                        Privileges::ODF_RECONDUCTION_OFFRE,
                        Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION,
                    ],
                    'assertion'  => Assertion\GestionAssertion::class,
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