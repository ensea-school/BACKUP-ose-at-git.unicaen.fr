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
            ],
        ],
    ],

    'controllers'        => [
        'invokables' => [
            'UnicaenAuth\Controller\Utilisateur' => Controller\UtilisateurController::class,
            'Application\Controller\Utilisateur' => Controller\UtilisateurController::class,
        ],
    ],
];