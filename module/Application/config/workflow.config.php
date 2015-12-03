<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'workflow' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/workflow',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Workflow',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'nav-next' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'nav-next',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [

                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => ['nav-next'],
                    'roles'      => ['user'],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Workflow' => Controller\WorkflowController::class,
        ],
        'initializers' => [
            Service\Workflow\WorkflowIntervenantAwareInitializer::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'WfEtapeService'            => Service\WfEtape::class,
            'WfIntervenantEtapeService' => Service\WfIntervenantEtape::class,
            'WorkflowIntervenant'       => Service\Workflow\WorkflowIntervenant::class,
            'DbFunctionRule'            => Rule\Intervenant\DbFunctionRule::class,
        ],
        'factories' => [
        ],
        'initializers' => [
            Service\Workflow\WorkflowIntervenantAwareInitializer::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'Workflow' => View\Helper\Workflow::class,
        ],
        'initializers' => [
            Service\Workflow\WorkflowIntervenantAwareInitializer::class,
        ],
    ],
];
