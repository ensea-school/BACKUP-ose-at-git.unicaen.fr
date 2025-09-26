<?php

namespace Workflow;

use Application\Provider\Privileges;


return [
    'routes' => [
        'workflow' => [
            'route'         => '/workflow',
            'may_terminate' => false,
            'child_routes'  => [
                'feuille-de-route-data'    => [
                    'route'      => '/feuille-de-route-data/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteData',
                    'privileges' => Privileges::INTERVENANT_FICHE,
                ],
                'feuille-de-route-refresh' => [
                    'route'      => '/feuille-de-route-refresh/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteRefresh',
                    'privileges' => Privileges::INTERVENANT_FICHE,
                ],
                'feuille-de-route-nav'     => [
                    'route'      => '/feuille-de-route-nav/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteNav',
                    'privileges' => Privileges::INTERVENANT_FICHE,
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\WorkflowController::class => Controller\WorkflowControllerFactory::class,
    ],

    'services' => [
        Service\WorkflowService::class       => Service\WorkflowServiceFactory::class,
        Service\TypeValidationService::class => Service\TypeValidationServiceFactory::class,
        Service\ValidationService::class     => Service\ValidationServiceFactory::class,
    ],
];