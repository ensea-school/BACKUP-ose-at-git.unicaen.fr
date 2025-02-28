<?php

namespace Workflow;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Assertion\AssertionFactory;


return [
    'routes' => [
        'workflow' => [
            'type'          => 'Literal',
            'options'       => [
                'route'    => '/workflow',
                'defaults' => [
                    'controller' => Controller\WorkflowController::class,
                    'action'     => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'dependances'               => [
                    'type'          => 'Literal',
                    'options'       => [
                        'route'    => '/dependances',
                        'defaults' => [
                            'controller' => Controller\WorkflowController::class,
                            'action'     => 'dependances',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'saisie'      => [
                            'type'    => 'Segment',
                            'options' => [
                                'route'       => '/saisie[/:wfEtapeDep]',
                                'defaults'    => [
                                    'controller' => Controller\WorkflowController::class,
                                    'action'     => 'saisieDep',
                                ],
                                'constraints' => [
                                    'wfEtapeDep' => '[0-9]*',
                                ],
                            ],
                        ],
                        'suppression' => [
                            'type'    => 'Segment',
                            'options' => [
                                'route'       => '/suppression/:wfEtapeDep',
                                'defaults'    => [
                                    'controller' => Controller\WorkflowController::class,
                                    'action'     => 'suppressionDep',
                                ],
                                'constraints' => [
                                    'wfEtapeDep' => '[0-9]*',
                                ],
                            ],
                        ],
                    ],
                ],
                'calculer-tout'             => [
                    'type'    => 'Literal',
                    'options' => [
                        'route'    => '/calculer-tout',
                        'defaults' => [
                            'controller' => Controller\WorkflowController::class,
                            'action'     => 'calculerTout',
                        ],
                    ],
                ],
                'feuille-de-route-refresh'  => [
                    'type'    => 'Segment',
                    'options' => [
                        'route'    => '/feuille-de-route-refresh/:intervenant',
                        'defaults' => [
                            'controller' => Controller\WorkflowController::class,
                            'action'     => 'feuilleDeRouteRefresh',
                        ],
                    ],
                ],
                'feuille-de-route-btn-next' => [
                    'type'    => 'Segment',
                    'options' => [
                        'route'       => '/feuille-de-route-btn-next/:wfEtapeCode/:intervenant',
                        'defaults'    => [
                            'controller' => Controller\WorkflowController::class,
                            'action'     => 'feuilleDeRouteBtnNext',
                        ],
                        'constraints' => [
                            'wfEtapeCode' => '[a-zA-Z0-9_-]*',
                        ],
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
                        'dependances' => [
                            'label'    => "Workflow",
                            'title'    => "Gestion des dépendances des feuilles de route",
                            'route'    => 'workflow/dependances',
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
    ],

    'forms' => [
        Form\DependanceForm::class => Form\DependanceFormFactory::class,
    ],

    'view_helpers' => [
        'feuilleDeRoute' => View\Helper\FeuilleDeRouteViewHelperFactory::class,
    ],
];