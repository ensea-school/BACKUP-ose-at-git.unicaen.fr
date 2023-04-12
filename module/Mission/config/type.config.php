<?php

namespace Mission;

use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Guard\PrivilegeController;


return [
    'routes' => [
        'missions-type' => [
            'route'         => '/missions-type',
            'controller'    => Controller\MissionTypeController::class,
            'action'        => 'index',
            'privileges'    => Privileges::MISSION_VISUALISATION_TYPE,
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'    => [
                    'route'      => '/saisir[/:typeMission]',
                    'controller' => Controller\MissionTypeController::class,
                    'action'     => 'saisir',
                    'privileges' => Privileges::MISSION_EDITION_TYPE,
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
                            'resource' => PrivilegeController::getResourceId(Controller\MissionTypeController::class, 'index'),
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