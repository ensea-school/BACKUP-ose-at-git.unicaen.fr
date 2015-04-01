<?php

namespace Application;

use Application\Acl\Role;
use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DirecteurComposanteRole;
use Application\Acl\GestionnaireComposanteRole;
use Application\Acl\ResponsableComposanteRole;
use Application\Acl\SuperviseurComposanteRole;
use Application\Acl\ResponsableRechercheLaboRole;
use Application\Acl\DrhRole;
use Application\Acl\GestionnaireDrhRole;
use Application\Acl\ResponsableDrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\SuperviseurEtablissementRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\FoadRole;
use Application\Acl\ResponsableFoadRole;

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
                    'notifier-indicateurs' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/notifier-indicateurs',
                            'defaults' => [
                                'action' => 'notifier-indicateurs',
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
                    'action'     => ['notifier-indicateurs'],
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