<?php

namespace Intervenant;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\AssertionFactory;
use Framework\Authorize\Authorize;


return [
    'routes' => [
        'statut' => [
            'route'         => '/statut',
            'controller'    => 'Intervenant\Controller\Statut',
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisie'    => [
                    'route'       => '/saisie[/:statut]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
                'delete'    => [
                    'route'       => '/delete/:statut',
                    'action'      => 'delete',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
                'trier'     => [
                    'route'  => '/trier',
                    'action' => 'trier',
                ],
                'dupliquer' => [
                    'route'       => '/dupliquer/:statut',
                    'action'      => 'dupliquer',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'intervenants' => [
                    'pages' => [
                        'statut' => [
                            'label'    => 'Statuts',
                            'route'    => 'statut',
                            'resource' => Authorize::controllerResource('Intervenant\Controller\Statut', 'index'),
                            'order'    => 40,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => Privileges::INTERVENANT_STATUT_EDITION,
            'resources'  => 'Statut',
            'assertion'  => Assertion\StatutAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => 'Intervenant\Controller\Statut',
            'action'     => ['index', 'saisie'],
            'privileges' => [Privileges::INTERVENANT_STATUT_VISUALISATION],
        ],
        [
            'controller' => 'Intervenant\Controller\Statut',
            'action'     => ['delete', 'trier', 'dupliquer'],
            'privileges' => [Privileges::INTERVENANT_STATUT_EDITION],
        ],
    ],


    'controllers' => [
        'Intervenant\Controller\Statut' => Controller\StatutControllerFactory::class,
    ],

    'services' => [
        Service\StatutService::class          => Service\StatutServiceFactory::class,
        Assertion\StatutAssertion::class      => AssertionFactory::class,
    ],


    'forms' => [
        Form\StatutSaisieForm::class => Form\StatutSaisieFormFactory::class,
    ],
];