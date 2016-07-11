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
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Parametre',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'generaux'         => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => 'generaux',
                            'defaults' => [
                                'action' => 'generaux',
                            ],
                        ],
                    ],
                    'campagnes-saisie' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => 'campagnes-saisie',
                            'defaults' => [
                                'action' => 'campagnes-saisie',
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
                    'gestion' => [
                        'pages' => [
                            'parametres' => [
                                'icon'     => 'glyphicon glyphicon-wrench',
                                'label'    => "Paramétrages",
                                'route'    => 'parametres',
                                'resource' => PrivilegeController::getResourceId('Application\Controller\Parametre', 'index'),
                                'pages'    => [
                                    'generaux'         => [
                                        'label'    => "Paramètres généraux",
                                        'route'    => 'parametres/generaux',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Parametre', 'generaux'),
                                    ],
                                    'campagnes-saisie' => [
                                        'label'    => "Campagnes de saisie des services",
                                        'route'    => 'parametres/campagnes-saisie',
                                        'resource' => PrivilegeController::getResourceId('Application\Controller\Parametre', 'campagnes-saisie'),
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
                    'action'     => ['generaux'],
                    'privileges' => [
                        Privileges::PARAMETRES_GENERAL_VISUALISATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Parametre',
                    'action'     => ['campagnes-saisie'],
                    'privileges' => [
                        Privileges::PARAMETRES_CAMPAGNES_SAISIE_VISUALISATION,
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
            'parametres'     => Form\ParametresForm::class,
            'campagneSaisie' => Form\CampagneSaisieForm::class,
        ],
    ],
];

