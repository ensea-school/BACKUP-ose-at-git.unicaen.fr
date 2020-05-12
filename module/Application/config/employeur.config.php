<?php

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'employeur' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/employeur',
                    'defaults' => [
                        'controller' => 'Application\Controller\Employeur',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
            'employeur-json' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/employeur/json',
                    'defaults' => [
                        'controller' => 'Application\Controller\Employeur',
                        'action'     => 'recherche-json',
                    ],
                ],
                'may_terminate' => true,
            ],

            ],
        ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages'    => [
                            'Employeurs' => [
                                'border-color' => '#9F491F',
                                'icon'         => 'glyphicon glyphicon-list-alt',
                                'label'        => "Employeurs",
                                'title'        => "Gestion des employeurs",
                                'route'        => 'employeur',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Discipline', 'index'),//creer un priviege employeur
                                'order'        => 70,
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
                'console-update-employeur' => [
                    'options' => [
                        'route'    => 'update-employeur',
                        'defaults' => [
                            'controller' => 'Application\Controller\Employeur',
                            'action'     => 'update-employeur',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers'        => [
        'factories' => [
            'Application\Controller\Employeur' => Controller\Factory\EmployeurControllerFactory::class
        ],
        'invokables' => [
            'Application\Controller\Employeur' => Controller\EmployeurController::class,
        ],
    ],

    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Employeur',
                    'action'     => ['index', 'recherche-json'],
                    'privileges' => Privileges::EMPLOYEUR_GESTION,
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Employeur' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::EMPLOYEUR_GESTION,
                        ],
                        'resources'  => 'Contrat',
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'invokables' => [
            Service\EmployeurService::class => Service\EmployeurService::class,
        ],
    ],
];