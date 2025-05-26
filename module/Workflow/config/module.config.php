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
                'administration' => [
                    'route'         => '/administration',
                    'controller'    => Controller\WorkflowController::class,
                    'action'        => 'administration',
                    'privileges'    => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'data'                   => [
                            'route'      => '/data',
                            'controller' => Controller\WorkflowController::class,
                            'action'     => 'administration-data',
                            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION],
                        ],
                        'tri'                    => [
                            'route'      => '/tri',
                            'controller' => Controller\WorkflowController::class,
                            'action'     => 'administration-tri',
                            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                        ],
                        'modification-etape' => [
                            'route'       => '/modification-etape/:workflowEtape',
                            'controller'  => Controller\WorkflowController::class,
                            'action'      => 'administration-modification-etape',
                            'privileges'  => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                            'constraints' => [
                                'workflowEtape'           => '[0-9]*',
                            ],
                        ],
                        'saisie-dependance'      => [
                            'route'       => '/saisie-dependance/:workflowEtape[/:workflowEtapeDependance]',
                            'controller'  => Controller\WorkflowController::class,
                            'action'      => 'administration-saisie-dependance',
                            'privileges'  => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                            'constraints' => [
                                'workflowEtape'           => '[0-9]*',
                                'workflowEtapeDependance' => '[0-9]*',
                            ],
                        ],
                        'suppression-dependance' => [
                            'route'       => '/suppression-dependance/:workflowEtapeDependance',
                            'controller'  => Controller\WorkflowController::class,
                            'action'      => 'administration-suppression-dependance',
                            'privileges'  => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                            'constraints' => [
                                'workflowEtapeDependance' => '[0-9]*',
                            ],
                        ],
                    ],
                ],


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

    'navigation' => [
        'administration' => [
            'pages' => [
                'configuration' => [
                    'pages' => [
                        'workflow' => [
                            'label'    => "Workflow",
                            'title'    => "Page d\'administration du workflow",
                            'route'    => 'workflow/administration',
                            'order'    => 60,
                            'resource' => Privileges::getResourceId(Privileges::WORKFLOW_DEPENDANCES_VISUALISATION),
                        ],
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
            'action'     => ['index', 'dependances'],
            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION, Privileges::WORKFLOW_DEPENDANCES_EDITION],
        ],
        [
            'controller' => Controller\WorkflowController::class,
            'action'     => ['saisieDep', 'suppressionDep', 'calculerTout'],
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

    'controllers' => [
        Controller\WorkflowController::class => Controller\WorkflowControllerFactory::class,
    ],

    'services' => [
        Service\WfEtapeDepService::class     => Service\WfEtapeDepServiceFactory::class,
        Service\WfEtapeService::class        => Service\WfEtapeServiceFactory::class,
        Service\TypeValidationService::class => Service\TypeValidationServiceFactory::class,
        Service\ValidationService::class     => Service\ValidationServiceFactory::class,
        Service\WorkflowService::class       => Service\WorkflowServiceFactory::class,
        Assertion\WorkflowAssertion::class   => AssertionFactory::class,
        Command\WorkflowResetCommand::class  => Command\WorflowResetCommandFactory::class,
    ],

    'forms' => [
        Form\DependanceForm::class => Form\DependanceFormFactory::class,
    ],

    'view_helpers' => [
        'feuilleDeRoute' => View\Helper\FeuilleDeRouteViewHelperFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'workflow-reset' => Command\WorkflowResetCommand::class,
        ],
    ],
];