<?php

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'contrat' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/employeur',
                    'defaults' => [
                        'controller' => 'Application\Controller\Employeur',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'creer'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/employeur/recherche',
                            'defaults'    => [
                                'action' => 'recherche',
                                ],
                            ],
                        ],
                    ]
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
                    'action'     => ['index'],
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