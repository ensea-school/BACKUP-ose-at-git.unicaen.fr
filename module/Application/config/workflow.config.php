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
            'Application\Controller\Workflow' => 'Application\Controller\WorkflowController',
        ],
        'initializers' => [
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'WfEtapeService'            => 'Application\\Service\\WfEtape',
            'WfIntervenantEtapeService' => 'Application\\Service\\WfIntervenantEtape',
            'WorkflowIntervenant'       => 'Application\\Service\\Workflow\\WorkflowIntervenant',
            'DbFunctionRule'            => 'Application\Rule\Intervenant\DbFunctionRule',
        ],
        'factories' => [
        ],
        'initializers' => [
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'Workflow' => 'Application\View\Helper\Workflow',
        ],
        'initializers' => [
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
    ],
];
