<?php

namespace Mission;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;


return [
    'routes' => [
        'intervenant' => [
            'child_routes' => [
                'missions-suivi' => [
                    'route'      => '/:intervenant/missions-suivi',
                    'controller' => Controller\SuiviController::class,
                    'action'     => 'index',
                    'privileges' => Privileges::MISSION_VISUALISATION_REALISE,
                    'assertion'  => Assertion\SuiviAssertion::class,
                ],
            ],
        ],
        'mission'     => [
            'child_routes' => [
                'suivi' => [
                    'route'        => '/suivi',
                    'child_routes' => [
                        'liste'     => [
                            'route'      => '/liste/:intervenant',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'liste',
                            'privileges' => Privileges::MISSION_VISUALISATION_REALISE,
                            'assertion'  => Assertion\SuiviAssertion::class,
                        ],
                        'ajout'   => [
                            'route'      => '/ajout/:intervenant/:date',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'ajout',
                            'privileges' => Privileges::MISSION_EDITION_REALISE,
                            'assertion'  => Assertion\SuiviAssertion::class,
                        ],
                        'modifier'  => [
                            'route'      => '/modifier/:volumeHoraireMission',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'modifier',
                            'privileges' => Privileges::MISSION_EDITION_REALISE,
                            'assertion'  => Assertion\SuiviAssertion::class,
                        ],
                        'supprimer' => [
                            'route'      => '/supprimer/:volumeHoraireMission',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'supprimer',
                            'privileges' => Privileges::MISSION_EDITION_REALISE,
                            'assertion'  => Assertion\SuiviAssertion::class,
                        ],
                        'valider'   => [
                            'route'      => '/valider/:volumeHoraireMission',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'valider',
                            'privileges' => Privileges::MISSION_VALIDATION_REALISE,
                            'assertion'  => Assertion\SuiviAssertion::class,
                        ],
                        'devalider' => [
                            'route'      => '/devalider/:volumeHoraireMission',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'devalider',
                            'privileges' => Privileges::MISSION_DEVALIDATION_REALISE,
                            'assertion'  => Assertion\SuiviAssertion::class,
                        ],
                    ],
                ],
            ],
        ],
    ],


    'navigation' => [
        'intervenant' => [
            'pages' => [
                'missions-suivi' => [
                    'label'               => "Suivi de missions",
                    'title'               => "Suivi de missions",
                    'route'               => 'intervenant/missions-suivi',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'withtarget'          => true,
                    'resource' => PrivilegeController::getResourceId(Controller\SuiviController::class, 'index'),
                    'order'               => 13,
                ],
            ],
        ],
    ],


    'rules' => [
        [
            'privileges' => [
                Privileges::MISSION_EDITION_REALISE,
                Privileges::MISSION_VALIDATION_REALISE,
                Privileges::MISSION_DEVALIDATION_REALISE
            ],
            'resources' => ['VolumeHoraireMission', 'Mission'],
            'assertion' => Assertion\SuiviAssertion::class,
        ],
    ],


    'controllers' => [
        Controller\SuiviController::class => Controller\SuiviControllerFactory::class,
    ],

    'forms' => [
        Form\MissionSuiviForm::class => Form\MissionSuiviFormFactory::class,
    ],

    'services' => [
        Assertion\SuiviAssertion::class => AssertionFactory::class,
    ],
];