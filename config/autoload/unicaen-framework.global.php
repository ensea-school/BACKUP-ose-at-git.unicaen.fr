<?php

use Utilisateur\Provider\UserProvider;
use Application\ORM\RouteEntitiesInjector;

return [
    'unicaen-framework' => [

        'preserve_session_keys' => [
            /* Clés de $_SESSION à préserver en cas de changement de profil ou d'utilisateur */
        ],

        'user_adapter' => UserProvider::class,

        'router' => [
            'firewalls' => [
                RouteEntitiesInjector::class,
            ],
        ],

        'api' => [
            'enabled' => true,
        ],
    ],

];