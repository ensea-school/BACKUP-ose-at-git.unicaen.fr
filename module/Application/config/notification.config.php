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
                    'notifier-indicateur-personnel' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/notifier-indicateur-personnel/:indicateur/:personnel',
                            'constraints' => [
                                'indicateur' => '[0-9]*',
                                'personnel'  => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'notifier-indicateur-personnel',
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
                        'route'    => 'notifier indicateurs [--pid=] [--debug] [--force] --requestUriHost= [--requestUriScheme=]',
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
                    'action'     => ['indicateurs', 'indicateur-fetch-title', 'notifier-indicateur-personnel'],
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
