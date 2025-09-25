<?php

namespace Service;

use Application\Provider\Privilege\Privileges;
use Framework\Authorize\Authorize;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Service\Controller\ModificationServiceDuController;
use Service\Controller\MotifModificationServiceController;
use Framework\Authorize\AssertionFactory;


return [
    'routes' => [
        'motif-modification-service' => [
            'route'         => '/motif-modification-service',
            'controller'    => MotifModificationServiceController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'delete' => [
                    'route'       => '/delete/:motifModificationServiceDu',
                    'action'      => 'delete',
                    'constraints' => [
                        'motif-modification-service' => '[0-9]*',
                    ],
                ],
                'saisie' => [
                    'route'       => '/saisie/[:motifModificationServiceDu]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'motif-modification-service' => '[0-9]*',
                    ],

                ],
            ],
        ],
        'intervenant'                => [
            'child_routes' => [
                'modification-service-du' => [
                    'route'      => '/:intervenant/modification-service-du',
                    'controller' => ModificationServiceDuController::class,
                    'action'     => 'saisir',
                ],
            ],
        ],
        'modification-service-du'    => [
            'route'        => '/modification-service-du',
            'controller'   => ModificationServiceDuController::class,
            'child_routes' => [
                'export-csv' => [
                    'route'  => '/export-csv',
                    'action' => 'export-csv',
                ],
            ],
        ],
    ],

    'navigation' => [
       'administration' => [
            'pages' => [
                'rh' => [
                    'pages' => [
                        'motif-modification-service' => [
                            'label'    => 'Motifs de modification du service dû',
                            'route'    => 'motif-modification-service',
                            'resource' => Authorize::controllerResource(MotifModificationServiceController::class, 'index'),
                            'order'    => 40,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
        'gestion' => [
            'pages' => [
                'pilotage' => [
                    'pages'    => [
                        'modification-service-du-export-csv' => [
                            'label'    => "Modifications de service dû (CSV)",
                            'title'    => "Modifications de service dû (CSV)",
                            'route'    => 'modification-service-du/export-csv',
                            'resource' => Authorize::controllerResource(ModificationServiceDuController::class, 'export-csv'),
                        ],
                    ],
                ],
            ],
        ],
        'intervenant'    => [
            'pages' => [
                'modification-service-du' => [
                    'label'        => "Modification de service dû",
                    'title'        => "Modification de service dû de l'intervenant {id}",
                    'route'        => 'intervenant/modification-service-du',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'resource'     => Authorize::controllerResource(ModificationServiceDuController::class, 'saisir'),
                    'order'        => 4,
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => Privileges::MODIF_SERVICE_DU_EDITION,
            'resources'  => 'Intervenant',
            'assertion'  => Assertion\ModificationServiceDuAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => MotifModificationServiceController::class,
            'action'     => ['index'],
            'privileges' => Privileges::MOTIFS_MODIFICATION_SERVICE_DU_VISUALISATION,
        ],
        [
            'controller' => MotifModificationServiceController::class,
            'action'     => ['saisie', 'delete'],
            'privileges' => Privileges::MOTIFS_MODIFICATION_SERVICE_DU_EDITION,
        ],
        [
            'controller' => ModificationServiceDuController::class,
            'action'     => ['saisir'],
            'privileges' => [
                Privileges::MODIF_SERVICE_DU_VISUALISATION,
            ],
            'assertion'  => Assertion\ModificationServiceDuAssertion::class,
        ],
        [
            'controller' => ModificationServiceDuController::class,
            'action'     => ['export-csv'],
            'privileges' => [
                Privileges::MODIF_SERVICE_DU_EXPORT_CSV,
            ],
        ],
    ],

    'controllers' => [
        MotifModificationServiceController::class => InvokableFactory::class,
        ModificationServiceDuController::class    => InvokableFactory::class,
    ],

    'services' => [
        Service\MotifModificationServiceDuService::class => InvokableFactory::class,
        Service\ModificationServiceDuService::class      => InvokableFactory::class,
        Assertion\ModificationServiceDuAssertion::class  => AssertionFactory::class,
    ],

    'forms' => [
        Form\MotifModificationServiceSaisieForm::class => InvokableFactory::class,
        Form\ModificationServiceDuForm::class          => InvokableFactory::class,
        Form\ModificationServiceDuFieldset::class      => InvokableFactory::class,
        Form\MotifModificationServiceDuFieldset::class => InvokableFactory::class,
    ],
];