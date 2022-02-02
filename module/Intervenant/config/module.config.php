<?php

namespace Intervenant;

use Application\Provider\Privilege\Privileges;

return [
    'routes' => [
        'statut' => [
            'options'       => [
                'route'       => '/statut',
                'constraints' => [
                    'statut' => '[0-9]*',
                ],
                'defaults'    => [
                    '__NAMESPACE__' => 'Intervenant\Controller',
                    'controller'    => 'Statut',
                    'action'        => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'saisie' => [
                    'options'       => [
                        'route'       => '/saisie[/:statut]',
                        'constraints' => [
                            'statut' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'saisie',
                        ],
                    ],
                    'may_terminate' => true,
                ],
                'delete' => [
                    'options'       => [
                        'route'       => '/delete/:statut',
                        'constraints' => [
                            'statut' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'delete',
                        ],
                    ],
                    'may_terminate' => true,
                ],
                'trier'  => [
                    'options'       => [
                        'route'      => '/trier',
                        'contraints' => [
                        ],
                        'defaults'   => [
                            'action' => 'trier',
                        ],
                    ],
                    'may_terminate' => 'true',
                ],
                'clone'  => [
                    'options'       => [
                        'route'       => '/clone/:statut',
                        'constraints' => [
                            'statut' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'clone',
                        ],
                    ],
                    'may_terminate' => true,
                ],
            ],
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
            'action'     => ['delete', 'trier', 'clone'],
            'privileges' => [Privileges::INTERVENANT_STATUT_EDITION],
        ],
    ],

    'controllers' => [
        'Intervenant\Controller\Statut' => Controller\StatutControllerFactory::class,
    ],

    'services' => [
        Service\StatutService::class => Service\StatutServiceFactory::class,
    ],


    'forms' => [
        Form\StatutSaisieForm::class => Form\StatutSaisieFormFactory::class,
    ],
];