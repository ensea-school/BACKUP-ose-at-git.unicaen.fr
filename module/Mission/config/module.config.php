<?php

namespace Mission;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mission\Controller\MissionController;
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
    ],

    'controllers' => [
        MissionController::class => Controller\MissionControllerFactory::class,
    ],

    'services' => [
        Assertion\MissionAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
    ],

    'forms' => [
    ],

    'view_helpers' => [
    ],
];