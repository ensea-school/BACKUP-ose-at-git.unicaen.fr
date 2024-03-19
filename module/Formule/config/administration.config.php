<?php

namespace Formule;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'formule' => [
            'child_routes' => [
                'administration' => [
                    'route'         => '/administration',
                    'controller'    => Controller\AdministrationController::class,
                    'action'        => 'index',
                    'privileges'    => [Privileges::FORMULE_ADMINISTRATION_VISUALISATION],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'telecharger-tableur' => [
                            'route' => '/telecharger-tableur/:formule',
                            'controller'    => Controller\AdministrationController::class,
                            'action'        => 'telecharger-tableur',
                            'privileges'    => [Privileges::FORMULE_ADMINISTRATION_VISUALISATION],
                        ],
                        'televerser-tableur' => [
                            'route' => '/televerser-tableur',
                            'controller'    => Controller\AdministrationController::class,
                            'action'        => 'televerser-tableur',
                            'privileges'    => [Privileges::FORMULE_ADMINISTRATION_EDITION],
                        ]
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'configuration' => [
                    'pages' => [
                        'formule-administration' => [
                            'label'    => 'Formules de calcul',
                            'route'    => 'formule/administration',
                            'resource' => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
                            'order'    => 50,
                            'color'    => '#0C8758',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\AdministrationController::class => Controller\AdministrationControllerFactory::class,
    ],

    'services' => [
        Service\FormulatorService::class => Service\FormulatorServiceFactory::class,
        Service\TraducteurService::class => Service\TraducteurServiceFactory::class,
    ],
];