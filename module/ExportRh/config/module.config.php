<?php

namespace ExportRh;

use Application\Provider\Privilege\Privileges;
use ExportRh\Assertion\ExportRhAssertion;
use ExportRh\Connecteur\Siham\SihamConnecteur;
use ExportRh\Connecteur\Siham\SihamConnecteurFactory;
use ExportRh\Form\ExportRhForm;
use ExportRh\Form\Factory\ExportRhFormFactory;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [

    'router'       => [
        'routes' => [
            'intervenant' => [
                'type'          => 'Segment',
                'may_terminate' => true,
                'options'       => [
                    'route'  => '/intervenant',
                    'action' => 'index',
                ],
                'child_routes'  => [
                    'exporter'       => [
                        'type'          => 'Segment',
                        'may_terminate' => false,
                        'options'       => [
                            'route'    => '/:intervenant/exporter',
                            'defaults' => [
                                'controller' => Controller\ExportRhController::class,
                                'action'     => 'exporter',
                            ],
                        ],
                    ],
                    'exporter-rh'    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/voir?tab=export-rh',
                            'defaults' => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'pec'            => [
                        'type'          => 'Segment',
                        'may_terminate' => false,
                        'options'       => [
                            'route'    => '/:intervenant/pec',
                            'defaults' => [
                                'controller' => Controller\ExportRhController::class,
                                'action'     => 'prise-en-charge',
                            ],
                        ],
                    ],
                    'ren'            => [
                        'type'          => 'Segment',
                        'may_terminate' => false,
                        'options'       => [
                            'route'    => '/:intervenant/ren',
                            'defaults' => [
                                'controller' => Controller\ExportRhController::class,
                                'action'     => 'renouvellement',
                            ],
                        ],
                    ],
                    'sync'           => [
                        'type'          => 'Segment',
                        'may_terminate' => false,
                        'options'       => [
                            'route'    => '/:intervenant/sync',
                            'defaults' => [
                                'controller' => Controller\ExportRhController::class,
                                'action'     => 'synchroniser',
                            ],
                        ],
                    ],
                    'administration' => [
                        'type'          => 'Literal',
                        'may_terminate' => true,
                        'options'       => [
                            'route'    => '/administration',
                            'defaults' => [
                                'controller' => Controller\AdministrationController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'child_routes'  => [
                            'chercher-intervenant-rh' => [
                                'type'          => 'Literal',
                                'may_terminate' => false,
                                'options'       => [
                                    'route'    => '/chercher-intervenant-rh',
                                    'defaults' => [
                                        'controller' => Controller\AdministrationController::class,
                                        'action'     => 'chercher-intervenant-rh',
                                    ],
                                ],

                            ],

                        ],
                    ],
                ],
            ],
        ],
    ],

    /* 'navigation'   => [
         'default' => [
             'home' => [
                 'pages' => [
                     'administration' => [
                         'pages' => [
                             'export-rh' => [
                                 'label'          => 'Export vers le SI RH',
                                 'icon'           => 'fas fa-table-list',
                                 'route'          => 'intervenant/administration',
                                 'resource'       => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'index'),
                                 'order'          => 82,
                                 'border - color' => '#111',
                                 'pages'          => [
                                     'chercher-intervenant-rh' => [
                                         'label'        => 'Rechercher un intervenant dans le SI RH',
                                         'icon'         => 'fas fa-graduation-cap',
                                         'route'        => 'intervenant/administration/chercher-intervenant-rh',
                                         'resource'     => PrivilegeController::getResourceId(Controller\AdministrationController::class, 'chercher-intervenant-rh'),
                                         'order'        => 800,
                                         'color' => '#BBCF55',
                                     ],
                                 ],
                             ],
                         ],

                     ],

                 ],
             ],
         ],
     ],*/
    'bjyauthorize' => [
        'guards'         => [
            PrivilegeController::class => [
                [
                    'controller' => Controller\AdministrationController::class,
                    'action'     => ['index', 'chercher-intervenant-rh'],
                    'privileges' => [Privileges::INTERVENANT_EXPORTER],
                    //'assertion'  => Assertion\AgrementAssertion::class,

                ],
                [
                    'controller' => Controller\ExportRhController::class,
                    'action'     => ['exporter', 'prise-en-charge', 'renouvellement', 'synchroniser'],
                    'privileges' => [Privileges::INTERVENANT_EXPORTER],
                    'assertion'  => ExportRhAssertion::class,

                ],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            Privileges::INTERVENANT_EXPORTER,
                            ExportRhAssertion::PRIV_CAN_INTERVENANT_EXPORT_RH,
                        ],
                        'resources'  => 'Intervenant',
                        'assertion'  => ExportRhAssertion::class,
                    ],
                ],
            ],
        ],
    ],


    'service_manager' => [
        'factories' => [
            Service\ExportRhService::class => Service\ExportRhServiceFactory::class,
            ExportRhAssertion::class       => \UnicaenAuth\Assertion\AssertionFactory::class,
            SihamConnecteur::class         => SihamConnecteurFactory::class,
        ],
    ],
    'view_helpers'    => [
        'factories' => [
        ],
    ],
    'controllers'     => [
        'factories' => [
            Controller\AdministrationController::class => Controller\AdministrationControllerFactory::class,
            Controller\ExportRhController::class       => Controller\ExportRhControllerFactory::class,
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'form_elements'   => [
        'factories' => [
            ExportRhForm::class => ExportRhFormFactory::class,
        ],
    ],
];
