<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'recherche' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/recherche/:action',
                    'constraints' => [
                        'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => 'Application\Controller\Recherche',
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Recherche',
                    'roles' => ['user']],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Recherche'   => Controller\RechercheController::class,
        ],
    ],
];