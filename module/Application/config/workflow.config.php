<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router' => [
        'routes' => [
            'workflow' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/workflow',
                    'defaults' => [
                        'controller' => 'Application\Controller\Workflow',
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
                    'calculer-tout'             => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/calculer-tout',
                            'defaults' => [
                                'action' => 'calculerTout',
                            ],
                        ],
                    ],
                    'feuille-de-route-refresh'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/feuille-de-route-refresh/:intervenant',
                            'defaults' => [
                                'action' => 'feuilleDeRouteRefresh',
                            ],
                        ],
                    ],
                    'feuille-de-route-btn-next' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/feuille-de-route-btn-next/:wfEtapeCode/:intervenant',
                            'defaults'    => [
                                'action' => 'feuilleDeRouteBtnNext',
                            ],
                            'constraints' => [
                                'wfEtapeCode' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'workflow-calcul-tbls' => [
                    'options' => [
                        'route'    => 'calcul-tableaux-bord',
                        'defaults' => [
                            'controller' => 'Application\Controller\Workflow',
                            'action'     => 'calcul-tableaux-bord',
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
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => ['feuilleDeRouteBtnNext'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_PREVU_EDITION,
                        Privileges::ENSEIGNEMENT_REALISE_EDITION,
                        Privileges::REFERENTIEL_PREVU_EDITION,
                        Privileges::REFERENTIEL_REALISE_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => ['feuilleDeRouteRefresh'],
                    'privileges' => [Privileges::INTERVENANT_FICHE],
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
                        'assertion' => Assertion\WorkflowAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Workflow' => Controller\WorkflowController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\WfEtapeDepService::class => Service\WfEtapeDepService::class,
            Service\WfEtapeService::class    => Service\WfEtapeService::class,
        ],
        'factories'  => [
            Service\WorkflowService::class     => Service\Factory\WorkflowServiceFactory::class,
            Assertion\WorkflowAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            Form\Workflow\DependanceForm::class => Form\Workflow\DependanceForm::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'feuilleDeRoute' => View\Helper\Intervenant\FeuilleDeRouteViewHelper::class,
        ],
    ],
];