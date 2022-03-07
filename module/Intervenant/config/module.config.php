<?php

namespace Intervenant;

use Application\Provider\Privilege\Privileges;
use Intervenant\Assertion\NoteAssertion;
use Intervenant\Entity\Db\Note;
use Intervenant\Entity\Db\Statut;


return [
    'routes' => [
        'note' => [
            'options' => [
                'route' => '/note/:intervenant',
                'defaults' => [
                    '__NAMESPACE__' => 'Intervenant\Controller',
                    'controller' => 'Note',
                    'action' => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes' => [
                'saisir' => [
                    'options' => [
                        'route' => '/saisir[/:note]',
                        'constraints' => [
                            'note' => '[0-9]*',
                        ],
                        'defaults' => [
                            'action' => 'saisir',
                        ],
                    ],
                    'may_terminate' => true,
                ],
                'voir' => [
                    'options' => [
                        'route' => '/voir[/:note]',
                        'constraints' => [
                            'note' => '[0-9]*',
                        ],
                        'defaults' => [
                            'action' => 'voir',
                        ],
                    ],
                    'may_terminate' => true,
                ],
                'supprimer' => [
                    'options' => [
                        'route' => '/supprimer/:note',
                        'constraints' => [
                            'note' => '[0-9]*',
                        ],
                        'defaults' => [
                            'action' => 'supprimer',
                        ],
                    ],
                    'may_terminate' => true,
                ],
            ],
        ],
        'statut' => [
            'route' => '/statut',
            'controller' => 'Intervenant\Controller\Statut',
            'action' => 'index',
            'may_terminate' => true,
            'child_routes' => [
                'saisie' => [
                    'route' => '/saisie[/:statut]',
                    'action' => 'saisie',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
                'delete' => [
                    'route' => '/delete/:statut',
                    'action' => 'delete',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
                'trier' => [
                    'route' => '/trier',
                    'action' => 'trier',
                ],
                'clone' => [
                    'route' => '/clone/:statut',
                    'action' => 'clone',
                    'constraints' => [
                        'statut' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'resources' => [
        'Statut',
    ],

    'rules' => [
        [
            'privileges' => Privileges::INTERVENANT_STATUT_EDITION,
            'resources' => 'Statut',
            'assertion' => Assertion\StatutAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => 'Intervenant\Controller\Statut',
            'action' => ['index', 'saisie'],
            'privileges' => [Privileges::INTERVENANT_STATUT_VISUALISATION],
        ],
        [
            'controller' => 'Intervenant\Controller\Statut',
            'action' => ['delete', 'trier', 'clone'],
            'privileges' => [Privileges::INTERVENANT_STATUT_EDITION],
        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action' => ['index'],
            'privileges' => [Privileges::INTERVENANT_NOTE_VISUALISATION],

        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action' => ['voir'],
            'privileges' => [Privileges::INTERVENANT_NOTE_VISUALISATION],

        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action' => ['saisir'],
            'assertion' => Assertion\NoteAssertion::class,
        ],
        [
            'controller' => 'Intervenant\Controller\Note',
            'action' => ['supprimer'],
            'assertion' => Assertion\NoteAssertion::class,
        ],
    ],

    'resources' => [
        'Note',
    ],

    'rules' => [
        [
            'privileges' => [
                NoteAssertion::PRIV_EDITER_NOTE,
                NoteAssertion::PRIV_SUPPRIMER_NOTE
            ],
            'resources' => 'Note',
            'assertion' => Assertion\NoteAssertion::class,
        ],
    ],

    'controllers' => [
        'Intervenant\Controller\Statut' => Controller\StatutControllerFactory::class,
        'Intervenant\Controller\Note' => Controller\NoteControllerFactory::class,
    ],

    'services' => [
        Service\TypeIntervenantService::class => Service\TypeIntervenantServiceFactory::class,
        Service\StatutService::class => Service\StatutServiceFactory::class,
        Service\NoteService::class => Service\NoteServiceFactory::class,
        Service\TypeNoteService::class => Service\TypeNoteServiceFactory::class,
        Assertion\NoteAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,

        Assertion\StatutAssertion::class => Assertion\StatutAssertionFactory::class,
    ],


    'forms' => [
        Form\StatutSaisieForm::class => Form\StatutSaisieFormFactory::class,
    ],
];