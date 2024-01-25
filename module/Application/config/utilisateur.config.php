<?php

return [
    'router' => [
        'routes' => [
            'utilisateur-recherche' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/utilisateur-recherche',
                    'defaults' => [
                        'controller' => \Application\Controller\UtilisateurController::class,
                        'action'     => 'recherche'
                    ],
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'changement-mot-de-passe' => [
                    'options' => [
                        'route'    => 'changement-mot-de-passe [--utilisateur=] [--mot-de-passe=]',
                        'defaults' => [
                            'controller' => \Application\Controller\UtilisateurController::class,
                            'action'     => 'changement-mot-de-passe',
                        ],
                    ],
                ],
                'creer-utilisateur'       => [
                    'options' => [
                        'route'    => 'creer-utilisateur [--data=]',
                        'defaults' => [
                            'controller' => \Application\Controller\UtilisateurController::class,
                            'action'     => 'creation',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => \Application\Controller\UtilisateurController::class,
                    'action'     => 'recherche',
                    'roles'      => ['user']],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            'UnicaenAuthentification\Controller\Utilisateur'     => Application\Controller\Factory\UtilisateurControllerFactory::class,
            \Application\Controller\UtilisateurController::class => Application\Controller\Factory\UtilisateurControllerFactory::class,
        ],
    ],
];