<?php

namespace Mission;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Mission\Controller\MissionController;
use Mission\Controller\MissionTypeController;
use Mission\Service\MissionTypeService;
use Mission\Service\MissionTypeServiceFactory;
use UnicaenAuth\Assertion\AssertionFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'intervenant'   => [
            'child_routes' => [
                'missions'       => [
                    'route'      => '/:intervenant/missions',
                    'controller' => MissionController::class,
                    'action'     => 'index',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'missions-suivi' => [
                    'route'      => '/:intervenant/missions-suivi',
                    'controller' => MissionController::class,
                    'action'     => 'suivi',
                    'privileges' => Privileges::MISSION_EDITION_REALISE,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'missions-suivi-data' => [
                    'route'      => '/:intervenant/missions-suivi-data',
                    'controller' => MissionController::class,
                    'action'     => 'suivi-data',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'missions-saisie-realise'         => [
                    'route'      => '/:intervenant/missions-saisie-realise',
                    'controller' => MissionController::class,
                    'action'     => 'saisie-realise',
                    'privileges' => Privileges::MISSION_EDITION_REALISE,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
            ],
        ],
        'mission'       => [
            'route'         => '/mission',
            'may_terminate' => false,
            'child_routes'  => [
                'liste'          => [
                    'route'      => '/liste/:intervenant',
                    'controller' => MissionController::class,
                    'action'     => 'liste',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'get'            => [
                    'route'      => '/get/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'get',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'ajout'          => [
                    'route'      => '/ajout/:intervenant',
                    'controller' => MissionController::class,
                    'action'     => 'ajout',
                    'privileges' => Privileges::MISSION_EDITION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'saisie'         => [
                    'route'      => '/saisie/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'saisie',
                    'privileges' => Privileges::MISSION_EDITION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'supprimer'      => [
                    'route'      => '/supprimer/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'supprimer',
                    'privileges' => Privileges::MISSION_EDITION,
                ],
                'valider'        => [
                    'route'      => '/valider/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'valider',
                    'privileges' => Privileges::MISSION_VALIDATION,
                ],
                'devalider'      => [
                    'route'      => '/devalider/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'devalider',
                    'privileges' => Privileges::MISSION_DEVALIDATION,
                ],
                'volume-horaire' => [
                    'route'         => '/volume-horaire',
                    'controller'    => MissionController::class,
                    'may_terminate' => false,
                    'child_routes'  => [
                        'supprimer' => [
                            'route'      => '/supprimer/:volumeHoraireMission',
                            'controller' => MissionController::class,
                            'action'     => 'volume-horaire-supprimer',
                            'privileges' => Privileges::MISSION_EDITION,
                        ],
                        'valider'   => [
                            'route'      => '/valider/:volumeHoraireMission',
                            'controller' => MissionController::class,
                            'action'     => 'volume-horaire-valider',
                            'privileges' => Privileges::MISSION_VALIDATION,
                        ],
                        'devalider' => [
                            'route'      => '/devalider/:volumeHoraireMission',
                            'controller' => MissionController::class,
                            'action'     => 'volume-horaire-devalider',
                            'privileges' => Privileges::MISSION_DEVALIDATION,
                        ],
                    ],
                ],
            ],
        ],
        'missions-type' => [
            'route'         => '/missions-type',
            'controller'    => MissionTypeController::class,
            'action'        => 'index',
            'privileges'    => Privileges::MISSION_VISUALISATION_TYPE,
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'    => [
                    'route'      => '/saisir[/:typeMission]',
                    'controller' => MissionTypeController::class,
                    'action'     => 'saisir',
                    'privileges' => Privileges::MISSION_EDITION_TYPE,
                ],
                'supprimer' => [
                    'route'      => '/supprimer/:typeMission',
                    'controller' => MissionTypeController::class,
                    'action'     => 'supprimer',
                    'privileges' => Privileges::MISSION_SUPPRESSION_TYPE,
                ],
            ],
        ],
    ],


    'navigation' => [
        'intervenant'    => [
            'pages' => [
                'missions'       => [
                    'label'               => "Missions",
                    'title'               => "Missions",
                    'route'               => 'intervenant/missions',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_MISSION_SAISIE,
                    'withtarget'          => true,
                    'visible'             => Assertion\MissionAssertion::class,
                    'order'               => 8,
                ],
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
        'administration' => [
            'pages' => [
                'intervenants' => [
                    'pages' => [
                        'missions-type' => [
                            'label'    => "Type de mission",
                            'route'    => 'missions-type',
                            'resource' => PrivilegeController::getResourceId(MissionTypeController::class, 'index'),
                            'order'    => 70,
                        ],
                    ],
                ],
            ],
        ],
    ],

    'rules' => [
    ],

    'controllers' => [
        MissionController::class     => Controller\MissionControllerFactory::class,
        MissionTypeController::class => Controller\MissionTypeControllerFactory::class,
    ],

    'services' => [
        MissionTypeService::class         => MissionTypeServiceFactory::class,
        Assertion\MissionAssertion::class => AssertionFactory::class,
        Service\MissionService::class     => Service\MissionServiceFactory::class,
    ],

    'forms' => [
        Form\MissionForm::class => Form\MissionFormFactory::class,
        Form\MissionSuiviForm::class => Form\MissionSuiviFormFactory::class,
    ],

    'view_helpers' => [

    ],
];