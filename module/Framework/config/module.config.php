<?php

namespace Framework;


use Framework\Cache\LaminasArrayStorageAdapter;

return [
    'unicaen-framework' => [
        'cache' => [

        ],
    ],



    'service_manager' => [
        'factories' => [
            'BjyAuthorize\Cache'                    => LaminasArrayStorageAdapter::class,
        ],
    ],
];