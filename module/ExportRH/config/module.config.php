<?php

namespace ExportRH;

return [

    'export-rh' => [
        'siham-ws' => [],
    ],

    'router' => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            Connecteur\Siham\SihamConnecteur::class => Connecteur\Siham\SihamConnecteurFactory::class,
        ],
    ],
    'view_helpers'    => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
//            'ExportRH\Controller\Index' => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
