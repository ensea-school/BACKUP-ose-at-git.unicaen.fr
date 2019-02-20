<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'administration'    => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/administration',
                    'defaults' => [
                        'controller'    => 'Application\Controller\Administration',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'changement-mot-de-passe' => [
                    'options' => [
                        'route'    => 'changement-mot-de-passe [--utilisateur=] [--mot-de-passe=]',
                        'defaults' => [
                            'controller' => 'Application\Controller\Administration',
                            'action'     => 'changement-mot-de-passe',
                        ],
                    ],
                ],
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
    'bjyauthorize'    => [
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
                        Privileges::TYPE_INTERVENTION_VISUALISATION,
                        Privileges::PIECE_JUSTIFICATIVE_GESTION_VISUALISATION,
                        Privileges::PLAFONDS_GESTION_VISUALISATION,
                        Privileges::CENTRES_COUTS_ADMINISTRATION_VISUALISATION,
                        Privileges::ETAT_SORTIE_ADMINISTRATION_VISUALISATION,
                        Privileges::TYPE_INTERVENTION_VISUALISATION,
                        Privileges::MODULATEUR_VISUALISATION,
                        Privileges::DOMAINES_FONCTIONNELS_ADMINISTRATION_VISUALISATION,
                    ],
                    'assertion'  => Assertion\GestionAssertion::class,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Administration'    => Controller\AdministrationController::class,
        ],
    ],
];