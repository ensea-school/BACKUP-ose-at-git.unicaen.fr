<?php

namespace Mission;

use Application\Entity\Db\WfEtape;
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
                    'privileges' => Privileges::MISSION_EDITION_REALISE,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
            ],
        ],
        'mission'     => [
            'child_routes' => [
                'suivi' => [
                    'route'        => '/suivi',
                    'child_routes' => [
                        'data' => [
                            'route'      => '/data/:intervenant',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'data',
                            'privileges' => Privileges::MISSION_VISUALISATION,
                            //'assertion'  => Assertion\MissionAssertion::class,
                        ],
                        'ajout' => [
                            'route'      => '/ajout/:intervenant/:date',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'ajout',
                            'privileges' => Privileges::MISSION_EDITION_REALISE,
                            //'assertion'  => Assertion\MissionAssertion::class,
                        ],
                        'modification' => [
                            'route'      => '/modification/:volumeHoraireMission',
                            'controller' => Controller\SuiviController::class,
                            'action'     => 'modification',
                            'privileges' => Privileges::MISSION_EDITION_REALISE,
                            //'assertion'  => Assertion\MissionAssertion::class,
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
                    'workflow-etape-code' => WfEtape::CODE_MISSION_SAISIE_REALISE,
                    'withtarget'          => true,
                    'visible'             => Assertion\MissionAssertion::class,
                    'order'               => 10,
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\SuiviController::class => Controller\SuiviControllerFactory::class,
    ],

    'forms' => [
        Form\MissionSuiviForm::class => Form\MissionSuiviFormFactory::class,
    ],

    'view_helpers' => [
    ],
];