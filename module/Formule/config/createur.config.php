<?php

namespace Formule;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'routes' => [
        'formule-createur' => [
            'route'         => '/formule-createur',
            'controller'    => Controller\CreateurController::class,
            'action'        => 'index',
            'privileges'    => [Privileges::FORMULE_CREATEUR],
            'may_terminate' => true,
            'child_routes'  => [

            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'configuration' => [
                    'pages' => [
                        'formule-createur' => [
                            'label'    => 'Créateur de formule de calcul',
                            'route'    => 'formule-createur',
                            'resource' => PrivilegeController::getResourceId(Controller\CreateurController::class, 'index'),
                            'order'    => 50,
                            'color'    => '#0C8758',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\CreateurController::class => Controller\CreateurControllerFactory::class,
    ],

    'services' => [
        Service\CreateurService::class => Service\CreateurServiceFactory::class,
    ],
];