<?php

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router' => [
        'routes' => [
            'employeur'        => [
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
            'employeur-search' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/employeur-search',
                    'defaults' => [
                        'controller' => 'Application\Controller\Employeur',
                        'action'     => 'recherche',
                    ],
                ],
                'may_terminate' => true,
            ],
            'employeur-json'   => [
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

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'rh' => [
                                'pages' => [
                                    'Employeurs' => [
                                        'color' => '#9F491F',
                                        'label'        => "Employeurs",
                                        'title'        => "Gestion des employeurs",
                                        'route'        => 'employeur',
                                        'resource'     => PrivilegeController::getResourceId('Application\Controller\Employeur', 'index'),
                                        'order'        => 20,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories'  => [
            'Application\Controller\Employeur' => Controller\Factory\EmployeurControllerFactory::class,
        ],
        'invokables' => [
            'Application\Controller\Employeur' => Controller\EmployeurController::class,
        ],
    ],

    'bjyauthorize' => [
        'guards'         => [
            PrivilegeController::class      => [
                [
                    'controller' => 'Application\Controller\Employeur',
                    'action'     => ['index', 'recherche-json'],
                    'privileges' => Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION,
                ],
            ],
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Employeur',
                    'roles'      => ['user']],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION,
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