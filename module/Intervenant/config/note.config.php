<?php

namespace Intervenant;

use Application\Provider\Privileges;
use Intervenant\Assertion\NoteAssertion;
use Intervenant\Entity\Db\Note;


return [
    'routes' => [
        'note' => [
            'route'         => '/note/:intervenant',
            'controller'    => Controller\NoteController::class,
            'action'        => 'index',
            'privileges'    => Privileges::INTERVENANT_NOTE_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'        => [
                    'route'       => '/saisir[/:note]',
                    'constraints' => [
                        'note' => '[0-9]*',
                    ],
                    'controller'  => Controller\NoteController::class,
                    'action'      => 'saisir',
                    'privileges'  => Privileges::INTERVENANT_NOTE_AJOUT,
                    'assertion'   => Assertion\NoteAssertion::class,
                ],
                'voir'          => [
                    'route'       => '/voir[/:note]',
                    'constraints' => [
                        'note' => '[0-9]*',
                    ],
                    'controller'  => Controller\NoteController::class,
                    'action'      => 'voir',
                    'privileges'  => [Privileges::INTERVENANT_NOTE_VISUALISATION],
                ],
                'supprimer'     => [
                    'route'       => '/supprimer/:note',
                    'constraints' => [
                        'note' => '[0-9]*',
                    ],
                    'controller'  => Controller\NoteController::class,
                    'action'      => 'supprimer',
                    'privileges'  => Privileges::INTERVENANT_NOTE_AJOUT,
                    'assertion'   => Assertion\NoteAssertion::class,
                ],
                'envoyer-email' => [
                    'route'      => '/envoyer-email',
                    'controller' => Controller\NoteController::class,
                    'action'     => 'envoyer-email',
                    'privileges' => [Privileges::INTERVENANT_NOTE_EMAIL],
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant-admin' => [
            'pages' => [
                'notes' => [
                    'label' => 'Notes',
                    'icon'  => 'fas fa-comment',
                    'route' => 'note',
                    'order' => 6,
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
            'resources'  => Note::class,
            'assertion'  => Assertion\NoteAssertion::class,
        ],
    ],

    'controllers' => [
        Controller\NoteController::class => Controller\NoteControllerFactory::class,
    ],

    'services' => [
        Service\NoteService::class     => Service\NoteServiceFactory::class,
        Service\TypeNoteService::class => Service\TypeNoteServiceFactory::class,
    ],
];