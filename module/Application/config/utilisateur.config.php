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