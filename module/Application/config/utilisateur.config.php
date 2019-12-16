<?php

return [
    'console' => [
        'router' => [
            'routes' => [
                'changement-mot-de-passe' => [
                    'options' => [
                        'route'    => 'changement-mot-de-passe [--utilisateur=] [--mot-de-passe=]',
                        'defaults' => [
                            'controller' => 'Application\Controller\Utilisateur',
                            'action'     => 'changement-mot-de-passe',
                        ],
                    ],
                ],
                'creer-utilisateur' => [
                    'options' => [
                        'route'    => 'creer-utilisateur [--data=]',
                        'defaults' => [
                            'controller' => 'Application\Controller\Utilisateur',
                            'action'     => 'creation',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers'        => [
        'factories' => [
            'UnicaenAuth\Controller\Utilisateur' => Application\Controller\Factory\UtilisateurControllerFactory::class,
        ],
        'invokables' => [
            'Application\Controller\Utilisateur' => Application\Controller\UtilisateurController::class,
        ],
    ],
];