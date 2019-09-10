<?php

namespace Application;

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
        'factory' => [
            'UnicaenAuth\Controller\Utilisateur' => Controller\Factory\UtilisateurControllerFactory::class,
        ],
        'invokables' => [
            'Application\Controller\Utilisateur' => Controller\UtilisateurController::class,
        ],
    ],
];