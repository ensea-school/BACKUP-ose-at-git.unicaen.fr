<?php

namespace Intervenant;

use Application\Provider\Privilege\Privileges;

return [
    'routes' => [
        'note'   => [
            'options'       => [
                'route'       => '/note/:intervenant/',
                'constraints' => [
                    'statut' => '[0-9]*',
                ],
                'defaults'    => [
                    '__NAMESPACE__' => 'Intervenant\Controller',
                    'controller'    => 'Note',
                    'action'        => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => [
            ],
        ],
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
                    'route'         => '/saisie[/:statut]',
                    'action'        => 'saisie',
                    'constraints'   => [
                        'statut' => '[0-9]*',
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
        [
            'controller' => 'Intervenant\Controller\Note',
            'action'     => ['index'],
            'privileges' => [Privileges::INTERVENANT_NOTE_VISUALISATION],
        ],
    ],

    'controllers' => [
        'Intervenant\Controller\Statut' => Controller\StatutControllerFactory::class,
        'Intervenant\Controller\Note'   => Controller\NoteControllerFactory::class,
    ],

    'services' => [
        Service\TypeIntervenantService::class => Service\TypeIntervenantServiceFactory::class,
        Service\StatutService::class          => Service\StatutServiceFactory::class,
        Service\NoteService::class   => Service\NoteServiceFactory::class,
        Assertion\StatutAssertion::class      => Assertion\StatutAssertionFactory::class,
    ],


    'forms' => [
        Form\StatutSaisieForm::class => Form\StatutSaisieFormFactory::class,
    ],
];