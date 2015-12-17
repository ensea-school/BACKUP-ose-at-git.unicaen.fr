<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [
    'router' => [
        'routes' => [
            'budget' => [
                'type'          => 'Literal',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/budget',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Budget',
                        'action'        => 'index',
                    ],
                ],
                'child_routes'  => [
                    'engagement' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/engagement',
                            'defaults' => [
                                'action' => 'engagement',
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
                            'budget' => [
                                'label'    => 'Budget',
                                'title'    => 'Budget',
                                'icon'     => 'fa fa-eur',
                                'border-color' => '#A22CAE',
                                'route'    => 'budget',
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Budget','index'),

                                'pages'    => [
                                    'engagement' => [
                                        'label'    => 'Engagement budgétaire',
                                        'title'    => 'Engagement budgétaire',
                                        'route'    => 'budget/engagement',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Budget','engagement'),
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
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Budget',
                    'action'     => ['engagement'],
                    'privileges' => [
                        Privileges::BUDGET_VISUALISATION,
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [

        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Budget' => Controller\BudgetController::class,
        ],
    ],
];