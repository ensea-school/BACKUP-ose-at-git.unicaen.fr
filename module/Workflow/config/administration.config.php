<?php

namespace Workflow;

use Application\Provider\Privileges;


return [
    'routes' => [
        'workflow' => [
            'child_routes' => [
                'administration' => [
                    'route'         => '/administration',
                    'controller'    => Controller\AdministrationController::class,
                    'action'        => 'index',
                    'privileges'    => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION],
                    'may_terminate' => true,
                    'child_routes'  => [
                        'data'                   => [
                            'route'      => '/data',
                            'controller' => Controller\AdministrationController::class,
                            'action'     => 'data',
                            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION],
                        ],
                        'rapport'                => [
                            'route'      => '/rapport',
                            'controller' => Controller\AdministrationController::class,
                            'action'     => 'rapport',
                            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION],
                        ],
                        'export'                 => [
                            'route'      => '/export',
                            'controller' => Controller\AdministrationController::class,
                            'action'     => 'export',
                            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_VISUALISATION],
                        ],
                        'import'                 => [
                            'route'      => '/import',
                            'controller' => Controller\AdministrationController::class,
                            'action'     => 'import',
                            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                        ],
                        'tri'                    => [
                            'route'      => '/tri',
                            'controller' => Controller\AdministrationController::class,
                            'action'     => 'tri',
                            'privileges' => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                        ],
                        'modification-etape'     => [
                            'route'       => '/modification-etape/:workflowEtape',
                            'controller'  => Controller\AdministrationController::class,
                            'action'      => 'modification-etape',
                            'privileges'  => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                            'constraints' => [
                                'workflowEtape' => '[0-9]*',
                            ],
                        ],
                        'saisie-dependance'      => [
                            'route'       => '/saisie-dependance/:workflowEtape[/:workflowEtapeDependance]',
                            'controller'  => Controller\AdministrationController::class,
                            'action'      => 'saisie-dependance',
                            'privileges'  => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                            'constraints' => [
                                'workflowEtape'           => '[0-9]*',
                                'workflowEtapeDependance' => '[0-9]*',
                            ],
                        ],
                        'suppression-dependance' => [
                            'route'       => '/suppression-dependance/:workflowEtapeDependance',
                            'controller'  => Controller\AdministrationController::class,
                            'action'      => 'suppression-dependance',
                            'privileges'  => [Privileges::WORKFLOW_DEPENDANCES_EDITION],
                            'constraints' => [
                                'workflowEtapeDependance' => '[0-9]*',
                            ],
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
                        'workflow' => [
                            'label'    => "Workflow",
                            'title'    => "Page d\'administration du workflow",
                            'route'    => 'workflow/administration',
                            'order'    => 60,
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
        Command\WorkflowResetCommand::class        => Command\WorflowResetCommandFactory::class,
        Service\RapportParametrageService::class   => Service\RapportParametrageServiceFactory::class,
        Service\TransfertParametrageService::class => Service\TransfertParametrageServiceFactory::class,
    ],

    'forms' => [
        Form\DependanceForm::class => Form\DependanceFormFactory::class,
        Form\EtapeForm::class      => Form\EtapeFormFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'workflow-reset' => Command\WorkflowResetCommand::class,
        ],
    ],
];