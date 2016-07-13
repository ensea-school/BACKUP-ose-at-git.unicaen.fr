<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'workflow' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/workflow',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Workflow',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'dependances'   => [
                        'type'          => 'Literal',
                        'options'       => [
                            'route'    => '/dependances',
                            'defaults' => [
                                'action' => 'dependances',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'saisie'      => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'       => '/saisie[/:wfEtapeDep]',
                                    'defaults'    => [
                                        'action' => 'saisieDep',
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
                                        'action' => 'suppressionDep',
                                    ],
                                    'constraints' => [
                                        'wfEtapeDep' => '[0-9]*',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'calculer-tout' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/calculer-tout',
                            'defaults' => [
                                'action' => 'calculerTout',
                            ],
                        ],
                    ],
                    'feuille-de-route-btn-next' => [
                        'type' => 'Segment',
                        'options' => [
                            'route'    => '/feuille-de-route-btn-next/:wfEtapeCode/:intervenant',
                            'defaults' => [
                                'action' => 'feuilleDeRouteBtnNext',
                            ],
                            'constraints' => [
                                'wfEtapeCode' => '[a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'workflow' => [
                                'label'        => "Workflow",
                                'title'        => "Workflow",
                                'route'        => 'workflow',
                                'icon'         => 'fa fa-gears',
                                'resource'     => Privileges::getResourceId(Privileges::WORKFLOW_DEPENDANCES_VISUALISATION),
                                'pages'        => [
                                    'dependances' => [
                                        'label'    => "Gestion des dépendances",
                                        'title'    => "Gestion des dépendances",
                                        'route'    => 'workflow/dependances',
                                        'resource' => Privileges::getResourceId(Privileges::WORKFLOW_DEPENDANCES_VISUALISATION),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => ['feuilleDeRouteBtnNext'],
                    'privileges' => [Privileges::ENSEIGNEMENT_EDITION, Privileges::REFERENTIEL_EDITION],
                ],
                [
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => ['index', 'dependances'],
                    'privileges' => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION, Privileges::WORKFLOW_DEPENDANCES_EDITION],
                ],
                [
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => ['saisieDep', 'suppressionDep', 'calculerTout'],
                    'privileges' => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'WorkflowResource' => [],
                'WorkflowEtape'    => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'resources' => ['WorkflowResource', 'WorkflowEtape'],
                        'assertion' => 'assertionWorkflow',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables'   => [
            'Application\Controller\Workflow' => Controller\WorkflowController::class,
        ],
    ],
    'service_manager' => [
        'invokables'   => [
            'applicationWfEtapeDep' => Service\WfEtapeDepService::class,
            'applicationWfEtape'    => Service\WfEtape::class,
            'workflow'              => Service\WorkflowService::class,
            'assertionWorkflow'     => Assertion\WorkflowAssertion::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'workflowDependance' => Form\Workflow\DependanceForm::class,
        ],
    ],
    'view_helpers'    => [
        'invokables'   => [
            'feuilleDeRoute' => View\Helper\Intervenant\FeuilleDeRouteViewHelper::class,
        ],
    ],
];