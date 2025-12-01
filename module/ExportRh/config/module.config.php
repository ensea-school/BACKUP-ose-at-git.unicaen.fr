<?php

namespace ExportRh;

use Application\Provider\Privileges;
use ExportRh\Assertion\ExportRhAssertion;
use ExportRh\Connecteur\Siham\SihamConnecteur;
use ExportRh\Connecteur\Siham\SihamConnecteurFactory;
use ExportRh\Form\ExportRhForm;
use ExportRh\Form\Factory\ExportRhFormFactory;
use Intervenant\Entity\Db\Intervenant;

return [

    'routes' => [
        'intervenant' => [
            'may_terminate' => true,
            'route'         => '/intervenant',
            'action'        => 'index',
            'child_routes'  => [
                'exporter' => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/exporter',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'exporter',
                    'privileges'    => Privileges::INTERVENANT_EXPORTER,
                    'assertion'     => ExportRhAssertion::class,
                ],
                'pec'      => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/pec',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'prise-en-charge',
                    'privileges'    => Privileges::INTERVENANT_EXPORTER,
                    'assertion'     => ExportRhAssertion::class,
                ],
                'ren'      => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/ren',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'renouvellement',
                    'privileges'    => Privileges::INTERVENANT_EXPORTER,
                    'assertion'     => ExportRhAssertion::class,
                ],
                'sync'     => [
                    'may_terminate' => false,
                    'route'         => '/:intervenant/sync',
                    'controller'    => Controller\ExportRhController::class,
                    'action'        => 'synchroniser',
                    'privileges'    => Privileges::INTERVENANT_EXPORTER,
                    'assertion'     => ExportRhAssertion::class,
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'pages' => [
                'export-rh'                   => [
                    'label' => 'Export RH',
                    'title' => 'Export vers le logiciel de gestion RH',
                    'route' => 'intervenant/exporter',
                    'order' => 10,
                ],
                'agrement-conseil-academique' => [
                    'label' => 'Agrément : Conseil académique',
                    'title' => 'Agrément : Conseil académique',
                    'route' => 'intervenant/agrement/conseil-academique',
                    'order' => 11,
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'resources'  => Intervenant::class,
            'privileges' => Privileges::INTERVENANT_EXPORTER,
            'assertion'  => ExportRhAssertion::class,
        ],
    ],

    'services'    => [
        Service\ExportRhService::class => Service\ExportRhServiceFactory::class,
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
