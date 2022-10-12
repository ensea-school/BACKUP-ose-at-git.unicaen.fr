<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;

return [

    /* Routes */
    'router'       => [
        'routes' => [
            'parametres' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/parametres',
                    'defaults' => [
                        'controller' => 'Application\Controller\Parametre',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'generaux' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/generaux',
                            'defaults' => [
                                'action' => 'generaux',
                            ],
                        ],
                    ],
                    'annees'   => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/annees',
                            'defaults' => [
                                'action' => 'annees',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    /* Menu */
    'navigation'   => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'parametres' => [
                                'icon'         => 'fas fa-wrench',
                                'label'        => "Paramétrages",
                                'route'        => 'parametres',
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Parametre', 'index'),
                                'border-color' => '#9B9B9B',
                                'order'        => 120,
                                'pages'        => [
                                    'annees'   => [
                                        'label'    => "Années",
                                        'route'    => 'parametres/annees',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Parametre', 'annees'),
                                    ],
                                    'generaux' => [
                                        'label'    => "Paramètres généraux",
                                        'route'    => 'parametres/generaux',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Parametre', 'generaux'),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    /* Droits d'accès */
    'bjyauthorize' => [
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
                    'action'     => ['annees'],
                    'privileges' => [
                        Privileges::PARAMETRES_ANNEES_VISUALISATION,
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
    'controllers'  => [
        'invokables' => [
            'Application\Controller\Parametre' => Controller\ParametreController::class,
        ],
    ],

    'form_elements' => [
        'invokables' => [
            Form\ParametresForm::class => Form\ParametresForm::class,
        ],
    ],
];

