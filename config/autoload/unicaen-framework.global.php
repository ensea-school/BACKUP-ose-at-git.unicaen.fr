<?php

use Utilisateur\Provider\UserProvider;
use Application\ORM\RouteEntitiesInjector;

return [
    'unicaen-framework' => [

        'preserve_session_keys' => [
            /* Clés de $_SESSION à préserver en cas de changement de profil ou d'utilisateur */

            // Année (et année d'import) choisies par l'utilisateur : elles ne doivent pas être perdues au changement de rôle
            \Application\Service\ContextService::class,
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