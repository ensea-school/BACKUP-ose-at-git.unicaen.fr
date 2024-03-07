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
    ],
];