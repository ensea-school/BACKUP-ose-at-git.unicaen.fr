<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
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
            ],
            'administration-type' => [
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
            'administration-centre-cout' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration-centre-cout',
                    'defaults' => [
                        'controller' => 'Application\Controller\Administration',
                        'action'     => 'administration-centre-cout',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'label'    => "Administration",
                        'route'    => 'administration',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Administration', 'index'),
                        'order'    => 7,
                        'pages'    => [
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
                    'action'     => ['index', 'administration-types', 'administration-centre-cout'],
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