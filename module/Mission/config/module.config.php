<?php

namespace Mission;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mission\Controller\MissionController;
use Mission\Controller\TauxMissionController;
use UnicaenAuth\Assertion\AssertionFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'intervenant' => [
            'child_routes' => [
                'missions' => [
                    'route'         => '/:intervenant/missions',
                    'controller'    => MissionController::class,
                    'action'        => 'index',
                    'may_terminate' => true,
                    'child_routes'  => [
                        /* Placez ici vos routes filles */
                    ],
                ],
            ],
        ],
        'tauxMission' => [
            'type'          => 'Literal',
            'options'       => [
                'route'    => '/taux-mission',
                'defaults' => [
                    'controller' => TauxMissionController::class,
                    'action'     => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => [
                /* Placez ici vos routes filles */
            ],
        ],
    ],


    'navigation' => [
        'intervenant' => [
            'pages' => [
                'missions' => [
                    'label'               => "Missions",
                    'title'               => "Missions",
                    'route'               => 'intervenant/missions',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_MISSION_SAISIE,
                    'withtarget'          => true,
                    'visible'             => Assertion\MissionAssertion::class,
                    'order'               => 4,
                ],
            ],
        ],
    ],

    'administration' => [
        'pages' => [
            'intervenants' => [
                'pages' => [
                    'taux-mission' => [
                        'label'      => "Taux de mission",
                        'title'      => "Editer et modifier les taux de mission",
                        'route'      => 'taux-missions',
                        'withtarget' => true,
                        'order'      => 10,
                        'visible'    => true,
                    ],
                ],
            ],
        ],
    ],

    'rules' => [
    ],

    'guards' => [
        [
            'controller' => MissionController::class,
            'action'     => ['index'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION,
            ],
            'assertion'  => Assertion\MissionAssertion::class,
        ],
        [
            'controller' => TauxMissionController::class,
            'action'     => ['index'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION,
            ],
        ],
    ],

    'controllers' => [
        MissionController::class     => Controller\MissionControllerFactory::class,
        TauxMissionController::class => Controller\TauxMissionControllerFactory::class,
    ],

    'services' => [
        Assertion\MissionAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
    ],

    'forms' => [
    ],

    'view_helpers' => [
    ],
];