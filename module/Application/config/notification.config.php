<?php

namespace Application;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;

return [
    'router' => [
        'routes' => [
            'notification' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/notification',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Notification',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'indicateurs' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/indicateurs',
                            'defaults' => [
                                'action' => 'indicateurs',
                            ],
                        ],
                    ],
                    'indicateur-fetch-title' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/indicateur-fetch-title',
                            'defaults' => [
                                'action' => 'indicateur-fetch-title',
                            ],
                        ],
                    ],
                    'indicateur-intervenants' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/indicateur-intervenants/:indicateur',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'indicateur-intervenants',
                            ],
                        ],
                    ],
                    'notifier-indicateurs' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/notifier-indicateurs',
                            'defaults' => [
                                'action' => 'notifier-indicateurs',
                            ],
                        ],
                    ],
                    'test-send-mail' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/test-send-mail',
                            'defaults' => [
                                'action' => 'test-send-mail',
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
                'notifier-indicateurs' => [
                    'type'    => 'Simple',
                    'options' => [
                        'route'    => 'notifier indicateurs [--force] --requestUriHost= [--requestUriScheme=]',
                        'defaults' => [
                            'controller' => 'Application\Controller\Notification',
                            'action'     => 'notifier-indicateurs'
                        ]
                    ]
                ]
            ]
        ]
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
                    'controller' => 'Application\Controller\Notification',
                    'action'     => ['indicateurs', 'indicateur-fetch-title'],
                    'roles'      => [AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\Notification',
                    'action'     => ['indicateur-intervenants'],
                    'roles'      => [ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID],
                ],
                [
                    'controller' => 'Application\Controller\Notification',
                    'action'     => ['notifier-indicateurs', 'test-send-mail'],
                    'roles'      => [],
                ],
            ],
        ],
//        'resource_providers' => array(
//            'BjyAuthorize\Provider\Resource\Config' => array(
//                'Intervenant' => [],
//            ),
//        ),
//        'rule_providers' => array(
//            'BjyAuthorize\Provider\Rule\Config' => array(
//                'allow' => array(
//                    array(
//                        $R_ALL,
//                        'Intervenant',
//                        array('total-heures-comp'),
//                        'IntervenantAssertion',
//                    ),
//                ),
//            ),
//        ),
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Notification' => 'Application\Controller\NotificationController',
        ],
        'initializers' => [
        ],
    ],
    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
        ],
        'initializers' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
        ],
        'initializers' => [
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
    ],
];
