<?php

namespace Enseignement;

use Application\Provider\Privilege\Privileges;
use Enseignement\Controller\EnseignementController;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'routes' => [
        'intervenant'  => [
            'child_routes' => [
                'enseignement-prevu'   => [
                    'route'      => '/:intervenant/enseignement-prevu',
                    'controller' => EnseignementController::class,
                    'action'     => 'prevu',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'PREVU',
                    ],
                ],
                'enseignement-realise' => [
                    'route'      => '/:intervenant/enseignement-realise',
                    'controller' => EnseignementController::class,
                    'action'     => 'realise',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'REALISE',
                    ],
                ],
            ],
        ],
        'enseignement' => [
            'route'        => '/enseignement',
            'controller'   => EnseignementController::class,
            'child_routes' => [
                /*      'prevu'   => [
                          'route'      => 'prevu',
                          'controller' => EnseignementController::class,
                          'action'     => 'prevu',
                          'defaults'   => [
                              'type-volume-horaire-code' => 'PREVU',
                          ],
                      ],
                      'realise' => [
                          'route'      => 'realise',
                          'controller' => EnseignementController::class,
                          'action'     => 'realise',
                          'defaults'   => [
                              'type-volume-horaire-code' => 'REALISE',
                          ],
                      ],*/
                'saisie'           => [
                    'route'       => '/saisie/:type-volume-horaire-code[/:service]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],

                ],
                'rafraichir-ligne' => [
                    'route'       => '/rafraichir-ligne/:service',
                    'action'      => 'rafraichir-ligne',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [

    ],

    'rules' => [

    ],

    'guards' => [
        [
            'controller' => EnseignementController::class,
            'action'     => ['prevu'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
            ],
            //'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['realise'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
            //'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['saisie', 'rafraichir-ligne'], // , 'suppression', , 'volumes-horaires-refresh', 'initialisation', 'constatation'
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_EDITION,
            ],
            //  'assertion'  => Assertion\ServiceAssertion::class,
        ],
    ],


    'controllers' => [
        EnseignementController::class => InvokableFactory::class,
    ],

    'services' => [
        Processus\EnseignementProcessus::class           => InvokableFactory::class,
        Processus\ValidationEnseignementProcessus::class => InvokableFactory::class,
        Service\ServiceService::class                    => InvokableFactory::class,
        Service\VolumeHoraireService::class              => InvokableFactory::class,
    ],


    'forms' => [
        Form\EnseignementSaisieForm::class => InvokableFactory::class,
    ],


    'view_helpers' => [
        'enseignements'          => View\Helper\EnseignementsFactory::class,
        'ligneEnseignement'      => View\Helper\LigneEnseignementFactory::class,
        'enseignementSaisieForm' => View\Helper\EnseignementSaisieFormFactory::class,
    ],
];