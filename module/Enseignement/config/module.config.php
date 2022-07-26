<?php

namespace Enseignement;

use Application\Provider\Privilege\Privileges;
use Enseignement\Controller\EnseignementController;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'routes' => [
        'intervenant' => [
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
        /*
                'enseignement' => [
                    'child_routes' => [
                        'prevu'   => [
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
                        ],
                    ],
                ],*/
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

    ],


    'view_helpers' => [
        'enseignements'     => View\Helper\EnseignementsFactory::class,
        'ligneEnseignement' => View\Helper\LigneEnseignementFactory::class,
    ],
];