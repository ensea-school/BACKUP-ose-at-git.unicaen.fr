<?php

namespace Mission;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;


return [
    'routes' => [
        'missions-type' => [
            'route'         => '/missions-type',
            'controller'    => Controller\MissionTypeController::class,
            'action'        => 'index',
            'privileges'    => Privileges::MISSION_VISUALISATION_TYPE,
            'may_terminate' => true,
            'child_routes'  => [
                'visualiser'    => [
                    'route'      => '/visualiser[/:typeMission]',
                    'controller' => Controller\MissionTypeController::class,
                    'action'     => 'visualiser',
                    'privileges' => Privileges::MISSION_VISUALISATION_TYPE,
                ],
                'saisir'    => [
                    'route'      => '/saisir[/:typeMission]',
                    'controller' => Controller\MissionTypeController::class,
                    'action'     => 'saisir',
                    'privileges' => Privileges::MISSION_EDITION_TYPE,
                ],
                'centre-couts'    => [
                    'route'      => '/centre-couts/:typeMission',
                    'controller' => Controller\MissionTypeController::class,
                    'action'     => 'centreCouts',
                    'privileges' => Privileges::MISSION_EDITION_CENTRE_COUT_TYPE,
                ],
                'centre-couts-supprimer'    => [
                    'route'      => '/centre-couts-supprimer/:typeMission/:centreCoutTypeMission',
                    'controller' => Controller\MissionTypeController::class,
                    'action'     => 'centreCoutsSupprimer',
                    'privileges' => Privileges::MISSION_EDITION_CENTRE_COUT_TYPE,
                ],
                'supprimer' => [
                    'route'      => '/supprimer/:typeMission',
                    'controller' => Controller\MissionTypeController::class,
                    'action'     => 'supprimer',
                    'privileges' => Privileges::MISSION_SUPPRESSION_TYPE,
                ],
            ],
        ],
    ],


    'navigation' => [
        'administration' => [
            'pages' => [
                'intervenants' => [
                    'pages' => [
                        'missions-type' => [
                            'label'    => "Types de missions",
                            'route'    => 'missions-type',
                            'resource' => Authorize::controllerResource(Controller\MissionTypeController::class, 'index'),
                            'order'    => 70,
                            'color'    => '#BBCF55',
                        ],
                    ],
                ],
            ],
        ],
    ],


    'controllers' => [
        Controller\MissionTypeController::class => Controller\MissionTypeControllerFactory::class,
    ],

    'services' => [
        Service\MissionTypeService::class => Service\MissionTypeServiceFactory::class,
    ],
];