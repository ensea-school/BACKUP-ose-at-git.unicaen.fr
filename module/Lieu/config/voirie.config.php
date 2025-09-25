<?php

namespace Lieu;


use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;

return [
    'routes' => [
        'voirie' => [
            'route'         => '/voirie',
            'controller'    => Controller\VoirieController::class,
            'action'        => 'index',
            'privileges'    => Privileges::REFERENTIEL_COMMUN_VOIRIE_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'saisie' => [
                    'route'       => '/saisie[/:voirie]',
                    'constraints' => [
                        'voirie' => '[0-9]*',
                    ],
                    'controller'  => Controller\VoirieController::class,
                    'action'      => 'saisie',
                    'privileges'  => Privileges::REFERENTIEL_COMMUN_VOIRIE_EDITION,
                ],
                'delete' => [
                    'route'       => '/delete/:voirie',
                    'constraints' => [
                        'voirie' => '[0-9]*',
                    ],
                    'controller'  => Controller\VoirieController::class,
                    'action'      => 'delete',
                    'privileges'  => Privileges::REFERENTIEL_COMMUN_VOIRIE_EDITION,
                ],
            ],
        ],
    ],

    'navigation' => [
        'administration' => [
            'pages' => [
                'nomenclatures' => [
                    'pages' => [
                        'voirie' => [
                            'label'    => 'Voiries',
                            'route'    => 'voirie',
                            'resource' => Authorize::controllerResource(Controller\VoirieController::class, 'index'),
                            'order'    => 50,
                            'color'    => '#BBCF55',
                        ],

                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\VoirieController::class => Controller\VoirieControllerFactory::class,
    ],

    'forms' => [
        Form\VoirieSaisieForm::class => Form\VoirieSaisieFormFactory::class,
    ],

    'services' => [
        Service\VoirieService::class => Service\VoirieServiceFactory::class,
    ],
];
