<?php

namespace Application;


use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router' => [
        'routes' => [
            'employeur'      => [
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
                    'saisie'    => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/saisie[/:employeur]',
                            'constraints' => [
                                'employeur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'supprimer' => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'       => '/supprimer/:employeur',
                            'constraints' => [
                                'employeur' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'supprimer',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
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

    'controllers' => [
        'factories'  => [
            'Application\Controller\Employeur' => Controller\Factory\EmployeurControllerFactory::class,
        ],
        'invokables' => [
            'Application\Controller\Employeur' => Controller\EmployeurController::class,
        ],
    ],

    'bjyauthorize' => [
        'guards'             => [
            PrivilegeController::class      => [
                [
                    'controller' => 'Application\Controller\Employeur',
                    'action'     => ['index', 'recherche-json', 'saisie'],
                    'privileges' => Privileges::REFERENTIEL_COMMUN_EMPLOYEUR_VISUALISATION,
                ],
            ],
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Employeur',
                    'roles'      => ['user']],
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