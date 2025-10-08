<?php

namespace Utilisateur;

return [
    'routes' => [
        'utilisateur-recherche' => [
            'route'      => '/utilisateur-recherche',
            'controller' => Controller\UtilisateurController::class,
            'action'     => 'recherche',
            'privileges' => ['user'],
        ],
    ],

    'controllers' => [
        Controller\UtilisateurController::class          => Controller\UtilisateurControllerFactory::class,
        'UnicaenAuthentification\Controller\Utilisateur' => Controller\UtilisateurControllerFactory::class,
    ],
];