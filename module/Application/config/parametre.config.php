<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [

    /* Routes */
    'router'          => [
        'routes' => [
            'parametres' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/parametres',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Parametre',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'generaux' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => 'generaux',
                            'defaults' => [
                                'action' => 'generaux'
                            ],
                        ],
                    ]
                ],
            ],
        ],
    ],

    /* Menu *
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'parametres' => [
                                'label'    => "Paramétrages",
                                'route'    => 'parametres',
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Parametre','index'),
                                'pages' => [
                                    'generaux' => [
                                        'label'    => "Paramètres généraux",
                                        'route'    => 'parametres/generaux',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Parametre','generaux'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],*/

    /* Droits d'accès */
    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Parametre',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::PARAMETRES_GENERAL_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Parametre',
                    'action'     => ['generaux'],
                    'privileges' => [
                        Privileges::PARAMETRES_GENERAL_VISUALISATION,
                    ],
                ],
            ],
        ],
    ],

    /* Déclaration du contrôleur */
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Parametre' => Controller\ParametreController::class,
        ],
    ],
];

