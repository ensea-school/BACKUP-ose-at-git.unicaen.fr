<?php

namespace ExportRh;

use Application\Provider\Privileges;
use ExportRh\Assertion\ExportRhAssertion;
use ExportRh\Connecteur\Siham\SihamConnecteur;
use ExportRh\Connecteur\Siham\SihamConnecteurFactory;
use ExportRh\Form\ExportRhForm;
use ExportRh\Form\Factory\ExportRhFormFactory;

return [

    'routes' => [
        'intervenant' => [
            'may_terminate' => true,
            'route'         => '/intervenant',
            'action'        => 'index',
            'child_routes'  => [
                'exporter'       => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/exporter',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'exporter',
                ],
                'exporter-rh'    => [
                    'route'  => '/:intervenant/voir?tab=export-rh',
                    'action' => 'voir',
                ],
                'pec'            => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/pec',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'prise-en-charge',
                ],
                'ren'            => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/ren',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'renouvellement',
                ],
                'sync'           => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/sync',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'synchroniser',
                ],
                'administration' => [
                    'may_terminate' => true,
                    'route'         => '/administration',
                    'controller'    => Controller\AdministrationController::class,
                    'action'        => 'index',
                    'child_routes'  => [
                        'chercher-intervenant-rh' => [
                            'may_terminate' => false,
                            'route'         => '/chercher-intervenant-rh',
                            'controller'    => Controller\AdministrationController::class,
                            'action'        => 'chercher-intervenant-rh',
                        ],
                    ],
                ],
            ],
        ],
    ],

    'guards' => [
        [
            'controller' => Controller\AdministrationController::class,
            'action'     => ['index', 'chercher-intervenant-rh'],
            'privileges' => [Privileges::INTERVENANT_EXPORTER],

        ],
        [
            'controller' => Controller\ExportRhController::class,
            'action'     => ['exporter', 'prise-en-charge', 'renouvellement', 'synchroniser'],
            'privileges' => [Privileges::INTERVENANT_EXPORTER],
            'assertion'  => ExportRhAssertion::class,

        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::INTERVENANT_EXPORTER,
                ExportRhAssertion::PRIV_CAN_INTERVENANT_EXPORT_RH,
            ],
            'resources'  => 'Intervenant',
            'assertion'  => ExportRhAssertion::class,
        ],
    ],

    'services'    => [
        Service\ExportRhService::class => Service\ExportRhServiceFactory::class,
        ExportRhAssertion::class       => \Framework\Authorize\AssertionFactory::class,
        SihamConnecteur::class         => SihamConnecteurFactory::class,
    ],
    'controllers' => [
        Controller\AdministrationController::class => Controller\AdministrationControllerFactory::class,
        Controller\ExportRhController::class       => Controller\ExportRhControllerFactory::class,
    ],
    'forms'       => [
        ExportRhForm::class => ExportRhFormFactory::class,
    ],
];
