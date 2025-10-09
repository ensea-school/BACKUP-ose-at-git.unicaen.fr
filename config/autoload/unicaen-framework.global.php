<?php

use Utilisateur\Provider\UserProvider;

return [
    'unicaen-framework' => [

        'preserve_session_keys' => [
            /* Clés de $_SESSION à préserver en cas de changement de profil ou d'utilisateur */
        ],

        /* Instance of Unicaen\Framework\User\UserAdapterInterface */
        'user_provider' => UserProvider::class,
    ],

];