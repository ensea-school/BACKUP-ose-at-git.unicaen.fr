<?php

namespace Intervenant;

use Application\Provider\Privilege\Privileges;
use Intervenant\Assertion\NoteAssertion;
use UnicaenPrivilege\Guard\PrivilegeController;


return [
    'routes' => [
        'note'   => [
            'options'       => [
                'route'    => '/note/:intervenant',
                'defaults' => [
                    '__NAMESPACE__' => 'Intervenant\Controller',
                    'controller'    => 'Note',
                    'action'        => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'        => [
                    'options'       => [
                        'route'       => '/saisir[/:note]',
                        'constraints' => [
                            'note' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'saisir',
                        ],
                    ],
                    'may_terminate' => true,
                ],
                'voir'          => [
                    'options'       => [
                        'route'       => '/voir[/:note]',
                        'constraints' => [
                            'note' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'voir',
                        ],
                    ],
                    'may_terminate' => true,
                ],
                'supprimer'     => [
                    'options'       => [
                        'route'       => '/supprimer/:note',
                        'constraints' => [
                            'note' => '[0-9]*',
                        ],
                        'defaults'    => [
                            'action' => 'supprimer',
                        ],
                    ],
                    'may_terminate' => true,
                ],
                'envoyer-email' => [
                    'options'       => [
                        'route'    => '/envoyer-email',
                        'defaults' => [
                            'action' => 'envoyer-email',
                        ],
                    ],
                    'may_terminate' => true,
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                NoteAssertion::PRIV_EDITER_NOTE,
                NoteAssertion::PRIV_SUPPRIMER_NOTE,
            ],
            'resources'  => 'Note',
            'assertion'  => Assertion\NoteAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => 'Intervenant\Controller\Note',
            'action'     => ['index'],
            'privileges' => [Privileges::INTERVENANT_NOTE_VISUALISATION],

        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action'     => ['voir'],
            'privileges' => [Privileges::INTERVENANT_NOTE_VISUALISATION],

        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action'     => ['envoyer-email'],
            'privileges' => [Privileges::INTERVENANT_NOTE_EMAIL],

        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action'     => ['saisir'],
            'assertion'  => Assertion\NoteAssertion::class,
        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action'     => ['supprimer'],
            'assertion'  => Assertion\NoteAssertion::class,
        ],
    ],


    'controllers' => [
        'Intervenant\Controller\Note'   => Controller\NoteControllerFactory::class,
    ],

    'services' => [
        Service\NoteService::class            => Service\NoteServiceFactory::class,
        Service\TypeNoteService::class        => Service\TypeNoteServiceFactory::class,
        Assertion\NoteAssertion::class        => \Framework\Authorize\AssertionFactory::class,
    ],
];