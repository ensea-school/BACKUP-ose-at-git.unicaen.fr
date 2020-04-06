<?php

namespace Application;


return [
    'console' => [
        'router' => [
            'routes' => [
                'console-update-employeur' => [
                    'options' => [
                        'route'    => 'update-employeur',
                        'defaults' => [
                            'controller' => 'Application\Controller\Employeur',
                            'action'     => 'update-employeur',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers'        => [
        'factories' => [
            'Application\Controller\Employeur' => Controller\Factory\EmployeurControllerFactory::class
        ],
        'invokables' => [
            'Application\Controller\Employeur' => Controller\EmployeurController::class,
        ],
    ],

    'service_manager' => [
        'invokables' => [
            Service\EmployeurService::class => Service\EmployeurService::class,
        ],
    ],
];