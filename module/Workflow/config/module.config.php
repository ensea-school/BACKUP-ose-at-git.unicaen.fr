<?php

namespace Workflow;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Assertion\AssertionFactory;


return [
    'routes' => [
        'workflow' => [
            'route'         => '/workflow',
            'may_terminate' => false,
            'child_routes'  => [
                'calculer-tout'             => [
                    'route'      => '/calculer-tout',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'calculerTout',
                ],
                'feuille-de-route-refresh'  => [
                    'route'      => '/feuille-de-route-refresh/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteRefresh',
                ],
                'feuille-de-route-btn-next' => [
                    'route'       => '/feuille-de-route-btn-next/:wfEtapeCode/:intervenant',
                    'controller'  => Controller\WorkflowController::class,
                    'action'      => 'feuilleDeRouteBtnNext',
                    'constraints' => [
                        'wfEtapeCode' => '[a-zA-Z0-9_-]*',
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\WorkflowController::class,
            'action'     => ['feuilleDeRouteBtnNext'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_EDITION,
            ],
        ],
        [
            'controller' => Controller\WorkflowController::class,
            'action'     => ['feuilleDeRouteRefresh'],
            'privileges' => [Privileges::INTERVENANT_FICHE],
        ],
        [
            'controller' => Controller\WorkflowController::class,
            'action'     => ['calculerTout'],
            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
        ],
    ],

    'resources' => [
        'WorkflowResource' => [],
        'WorkflowEtape'    => [],
    ],

    'rules' => [
        [
            'resources' => ['WorkflowResource', 'WorkflowEtape'],
            'assertion' => Assertion\WorkflowAssertion::class,
        ],
    ],

    'services' => [
        Service\WfEtapeDepService::class     => Service\WfEtapeDepServiceFactory::class,
        Service\WfEtapeService::class        => Service\WfEtapeServiceFactory::class,
        Service\TypeValidationService::class => Service\TypeValidationServiceFactory::class,
        Service\ValidationService::class     => Service\ValidationServiceFactory::class,
        Service\WorkflowService::class       => Service\WorkflowServiceFactory::class,
        Assertion\WorkflowAssertion::class   => AssertionFactory::class,
    ],

    'view_helpers' => [
        'feuilleDeRoute' => View\Helper\FeuilleDeRouteViewHelperFactory::class,
    ],
];