<?php

namespace Framework;


use Framework\Cache\LaminasArrayStorageAdapter;
use Framework\View\Helper\IsAllowedFactory;
use Utilisateur\Provider\UserProvider;

return [
    'unicaen-framework' => [
        'cache' => [

        ],

        'preserve_session_keys' => [
            'initialized',
            '__Laminas',
            'Laminas_Auth',
            'FlashMessenger',
            'Framework\User\UserManager',
            'UnicaenAuthentification\Service\UserContext',
            'UnicaenAuthentification\Authentication\Adapter\Db',
            'UnicaenAuthentification\Authentication\Storage\Usurpation',
        ],

        'user_provider' => UserProvider::class,
    ],

    'service_manager' => [
        'factories' => [
            'BjyAuthorize\Cache' => LaminasArrayStorageAdapter::class,
        ],
    ],

    'view_helpers' => [
        'isAllowed' => IsAllowedFactory::class,
    ],

    'controller_plugins' => [
        'factories' => [
            'isAllowed' => \Framework\Controller\Plugin\IsAllowedFactory::class,
        ],
    ],
];