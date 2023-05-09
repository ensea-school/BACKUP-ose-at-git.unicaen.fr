<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'router'          => [
        'routes' => [
            'gestion' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/gestion',
                    'defaults' => [
                        'controller' => 'Application\Controller\Gestion',
                        'action'     => 'index',
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
                    'gestion' => [
                        'label'    => "Gestion",
                        'route'    => 'gestion',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Gestion', 'index'),
                        'order'    => 6,
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
                    'controller' => 'Application\Controller\Gestion',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::MISE_EN_PAIEMENT_EXPORT_PAIE,
                        Privileges::MISE_EN_PAIEMENT_VISUALISATION_GESTION,
                        Privileges::AGREMENT_CONSEIL_ACADEMIQUE_VISUALISATION,
                        Privileges::AGREMENT_CONSEIL_RESTREINT_VISUALISATION,
                        Privileges::PILOTAGE_VISUALISATION,
                        Privileges::BUDGET_VISUALISATION,
                        Privileges::INDICATEUR_VISUALISATION,
                        Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
                    ],
                    'assertion'  => Assertion\GestionAssertion::class,
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Gestion' => Controller\GestionController::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Assertion\GestionAssertion::class => \UnicaenPrivilege\Assertion\AssertionFactory::class,
        ],
    ],
];