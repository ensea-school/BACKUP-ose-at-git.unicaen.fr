<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'paiement' => [
                'type'    => 'Literal',
                'options' => [
                    'route' => '/paiement',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Paiement',
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Paiement',
                    'action'     => ['index','miseEnPaiement'],
                    'roles'      => [R_COMPOSANTE, R_ADMINISTRATEUR],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'paiementLigne' => 'Application\View\Helper\Paiement\LigneViewHelper',
        ],
    ],
    'form_elements' => [
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Paiement' => 'Application\Controller\PaiementController',
        ],
//        'aliases' => [
//            'PaiementController' => 'Application\Controller\Paiement',
//        ],
    ],
];