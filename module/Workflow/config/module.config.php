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
                'feuille-de-route-diagnostic' => [
                    'route'      => '/feuille-de-route-diagnostic/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteDiagnostic',
                    'privileges' => Privileges::WORKFLOW_DEPENDANCES_VISUALISATION,
                ],
                'feuille-de-route-etat-lieux' => [
                    'route'      => '/feuille-de-route-etat-lieux/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteEtatLieux',
                    'privileges' => Privileges::WORKFLOW_DEPENDANCES_VISUALISATION,
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
        Service\WorkflowService::class                 => Service\WorkflowServiceFactory::class,
        Service\DiagnosticFeuilleDeRouteService::class => Service\DiagnosticFeuilleDeRouteServiceFactory::class,
        Service\EtatLieuxIntervenantService::class     => Service\EtatLieuxIntervenantServiceFactory::class,
    ],
];