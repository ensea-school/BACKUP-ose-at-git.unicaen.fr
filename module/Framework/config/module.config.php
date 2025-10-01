<?php

namespace Framework;


use Framework\Cache\LaminasArrayStorageAdapter;
use Utilisateur\Provider\UserProvider;

return [
    'unicaen-framework' => [
        'cache' => [

        ],

        'user_provider' => UserProvider::class,
    ],


    'service_manager' => [
        'factories' => [
            'BjyAuthorize\Cache'                    => LaminasArrayStorageAdapter::class,
        ],
    ],
];