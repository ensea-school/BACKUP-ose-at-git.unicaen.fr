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
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Administration',
                        'action'        => 'index',
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
                    ],
                    'assertion'  => 'AssertionGestion',
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