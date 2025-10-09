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
        Command\WorkflowResetCommand::class => Command\WorflowResetCommandFactory::class,
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