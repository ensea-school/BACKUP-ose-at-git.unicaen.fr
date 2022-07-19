<?php

namespace Service;

use Application\Provider\Privilege\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Service\Controller\CampagneSaisieController;
use Service\Controller\CampagneSaisieControllerFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'parametres' => [
            'child_routes' => [
                'campagnes-saisie' => [
                    'controller' => CampagneSaisieController::class,
                    'route'      => '/campagnes-saisie',
                    'action'     => 'campagnes-saisie',
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'parametres' => [
                    'pages' => [
                        'campagnes-saisie' => [
                            'label'    => "Campagnes de saisie des services",
                            'route'    => 'parametres/campagnes-saisie',
                            'resource' => PrivilegeController::getResourceId(CampagneSaisieController::class, 'campagnes-saisie'),
                        ],
                    ],
                ],
            ],
        ],
    ],

    'resources' => [

    ],

    'rules' => [

    ],

    'guards' => [
        [
            'controller' => CampagneSaisieController::class,
            'action'     => ['campagnes-saisie'],
            'privileges' => [
                Privileges::PARAMETRES_CAMPAGNES_SAISIE_VISUALISATION,
            ],
        ],
    ],


    'controllers' => [
        CampagneSaisieController::class => CampagneSaisieControllerFactory::class,
    ],

    'services' => [
        Service\EtatVolumeHoraireService::class => InvokableFactory::class,
        Service\TypeVolumeHoraireService::class => InvokableFactory::class,
        Service\CampagneSaisieService::class    => InvokableFactory::class,
    ],


    'forms' => [
        Form\CampagneSaisieForm::class => InvokableFactory::class,
    ],
];