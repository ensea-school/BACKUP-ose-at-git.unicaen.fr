<?php

namespace Workflow;

use Application\Provider\Privilege\Privileges;
use Framework\Container\AutowireFactory;
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
                    'privileges' => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                ],
                'feuille-de-route-data'          => [
                    'route'      => '/feuille-de-route-data/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteData',
                    'privileges' => Privileges::INTERVENANT_FICHE,
                ],
                'feuille-de-route-refresh'  => [
                    'route'      => '/feuille-de-route-refresh/:intervenant',
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'feuilleDeRouteRefresh',
                    'privileges' => Privileges::INTERVENANT_FICHE,
                ],
                'feuille-de-route-btn-next' => [
                    'route'       => '/feuille-de-route-btn-next/:wfEtapeCode/:intervenant',
                    'controller'  => Controller\WorkflowController::class,
                    'action'      => 'feuilleDeRouteBtnNext',
                    'constraints' => [
                        'wfEtapeCode' => '[a-zA-Z0-9_-]*',
                    ],
                    'privileges'  => [
                        Privileges::ENSEIGNEMENT_PREVU_EDITION,
                        Privileges::ENSEIGNEMENT_REALISE_EDITION,
                        Privileges::REFERENTIEL_PREVU_EDITION,
                        Privileges::REFERENTIEL_REALISE_EDITION,
                    ],
                ],
            ],
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

    'controllers' => [
        Controller\WorkflowController::class => Controller\WorkflowControllerFactory::class,
    ],

    'services' => [
        Service\WorkflowService::class       => Service\WorkflowServiceFactory::class,
        Service\WfEtapeDepService::class     => Service\WfEtapeDepServiceFactory::class,
        Service\WfEtapeService::class        => Service\WfEtapeServiceFactory::class,
        Service\TypeValidationService::class => Service\TypeValidationServiceFactory::class,
        Service\ValidationService::class     => Service\ValidationServiceFactory::class,
        Assertion\WorkflowAssertion::class   => AssertionFactory::class,
    ],

    'view_helpers' => [
        'feuilleDeRoute' => View\Helper\FeuilleDeRouteViewHelperFactory::class,
    ],
];