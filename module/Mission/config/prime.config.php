<?php

namespace Mission;

use Application\Provider\Privilege\Privileges;
use Mission\Controller\PrimeController;
use UnicaenPrivilege\Guard\PrivilegeController;


return [
    'routes' => [
        'intervenant' => [
            'child_routes' => [
                'prime'                       => [
                    'route'      => '/:intervenant/prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'index',
                    //                    'privileges' => Privileges::MISSION_VISUALISATION_REALISE,
                    //                    'assertion'  => Assertion\SuiviAssertion::class,
                ],
                'get-contrat-prime'           => [
                    'route'      => '/:intervenant/get-contrat-prime',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'get-contrat-prime',

                ],
                'declaration-prime'           => [
                    'route'      => '/:intervenant/declaration-prime/:contrat',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'declaration-prime',

                ],
                'supprimer-declaration-prime' => [
                    'route'      => '/:intervenant/supprimer-declaration-prime/:contrat',
                    'controller' => Controller\PrimeController::class,
                    'action'     => 'supprimer-declaration-prime',

                ],
            ],

        ],

    ],


    'navigation' => [
        'intervenant' => [
            'pages' => [
                'prime' => [
                    'label'        => "Prime de fin de missions",
                    'title'        => "Prime de fin de missions",
                    'route'        => 'intervenant/prime',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'resource'     => PrivilegeController::getResourceId(Controller\PrimeController::class, 'index'),
                    'order'        => 14,
                ],
            ],
        ],
    ],


    /* 'rules' => [
         [
             'privileges' => [
                 Privileges::MISSION_EDITION_REALISE,
                 Privileges::MISSION_VALIDATION_REALISE,
                 Privileges::MISSION_DEVALIDATION_REALISE,
             ],
             'resources'  => 'VolumeHoraireMission',
             'assertion'  => Assertion\SuiviAssertion::class,
         ],
     ],*/

    'guards' => [
        [
            'controller' => PrimeController::class,
            'action'     => ['index', 'get-contrat-prime', 'declaration-prime', 'supprimer-declaration-prime'],
            'privileges' => [
                Privileges::MISSION_PRIME_VISUALISATION,
            ],
        ],
    ],


    'controllers' => [
        Controller\PrimeController::class => Controller\PrimeControllerFactory::class,
    ],

    /* 'forms' => [
         Form\MissionSuiviForm::class => Form\MissionSuiviFormFactory::class,
     ],*/

];