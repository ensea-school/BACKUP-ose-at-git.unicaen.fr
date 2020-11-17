<?php

namespace Application;

return [

    'router' => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
        ],
    ],
    'view_helpers'    => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            'ExportRH\Controller\Index' => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
