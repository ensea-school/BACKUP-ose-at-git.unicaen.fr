<?php

namespace Enseignement;

use Application\Provider\Privilege\Privileges;
use Enseignement\Controller\IntervenantController;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'routes' => [
        'intervenant' => [
            'child_routes' => [
                'enseignements-prevus'   => [
                    'route'      => '/:intervenant/enseignements-prevus',
                    'controller' => IntervenantController::class,
                    'action'     => 'prevu',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'PREVU',
                    ],
                ],
                'enseignements-realises' => [
                    'route'      => '/:intervenant/enseignements-realises',
                    'controller' => IntervenantController::class,
                    'action'     => 'realise',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'REALISE',
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
            'controller' => IntervenantController::class,
            'action'     => ['prevu'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
            ],
            //'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => IntervenantController::class,
            'action'     => ['realise'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
            //'assertion'  => Assertion\ServiceAssertion::class,
        ],
    ],


    'controllers' => [
        IntervenantController::class => InvokableFactory::class,
    ],

    'services' => [
        Processus\EnseignementProcessus::class           => InvokableFactory::class,
        Processus\ValidationEnseignementProcessus::class => InvokableFactory::class,
        Service\ServiceService::class                    => InvokableFactory::class,
        Service\VolumeHoraireService::class              => InvokableFactory::class,
    ],


    'forms' => [

    ],
];