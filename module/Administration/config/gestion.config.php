<?php

namespace Administration;

return [
    'routes' => [
        'gestion' => [
            'route'         => '/gestion',
            'controller'    => Controller\GestionController::class,
            'action'        => 'index',
            'privileges'    => 'user',
            'assertion'     => Assertion\GestionAssertion::class,
            'may_terminate' => true,
        ],
    ],

    'navigation' => [
        'gestion' => [
            'label' => "Gestion",
            'route' => 'gestion',
            'order' => 6,
            'pages' => [
            ],
        ],
    ],

    'controllers' => [
        Controller\GestionController::class => Controller\GestionControllerFactory::class,
    ],
];