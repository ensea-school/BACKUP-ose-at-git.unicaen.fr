<?php

namespace Mission;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Mission\Controller\MissionController;
use Mission\Controller\MissionTauxController;
use Mission\Controller\MissionTypeController;
use Mission\Service\MissionTauxService;
use Mission\Service\MissionTauxServiceFactory;
use Mission\Service\MissionTypeService;
use Mission\Service\MissionTypeServiceFactory;
use UnicaenAuth\Assertion\AssertionFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'intervenant'   => [
            'child_routes' => [
                'missions' => [
                    'route'         => '/:intervenant/missions',
                    'controller'    => MissionController::class,
                    'action'        => 'index',
                    'may_terminate' => true,
                    'child_routes'  => [
                        'modification' => [
                            'route'      => '/modification',
                            'controller' => MissionController::class,
                            'action'     => 'modification',
                        ],
                    ],
                ],
            ],
        ],
        'mission'       => [
            'route'         => '/mission',
            'may_terminate' => false,
            'child_routes'  => [
                'liste'     => [
                    'route'      => '/liste/:intervenant',
                    'controller' => MissionController::class,
                    'action'     => 'liste',
                ],
                'modifier'  => [
                    'route'      => '/modifier',
                    'controller' => MissionController::class,
                    'action'     => 'modifier',
                ],
                'supprimer' => [
                    'route'      => '/supprimer',
                    'controller' => MissionController::class,
                    'action'     => 'supprimer',
                ],
                'valider'   => [
                    'route'      => '/valider',
                    'controller' => MissionController::class,
                    'action'     => 'valider',
                ],
                'devalider' => [
                    'route'      => '/devalider',
                    'controller' => MissionController::class,
                    'action'     => 'devalider',
                ],
            ],
        ],
        'missions-taux' => [
            'route'         => '/missions-taux',
            'controller'    => MissionTauxController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'           => [
                    'route'      => '/saisir[/:missionTauxRemu]',
                    'controller' => MissionTauxController::class,
                    'action'     => 'saisir',
                ],
                'supprimer'        => [
                    'route'      => '/supprimer/:missionTauxRemu',
                    'controller' => MissionTauxController::class,
                    'action'     => 'supprimer',
                ],
                'saisir-valeur'    => [
                    'route'      => '/saisir-valeur[/:missionTauxRemu][/:missionTauxRemuValeur]',
                    'controller' => MissionTauxController::class,
                    'action'     => 'saisirValeur',
                ],
                'supprimer-valeur' => [
                    'route'      => '/supprimer-valeur/:missionTauxRemuValeur',
                    'controller' => MissionTauxController::class,
                    'action'     => 'supprimerValeur',
                ],
            ],
        ],
        'missions-type' => [
            'route'         => '/missions-type',
            'controller'    => MissionTypeController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                ]
        ]
    ],


    'navigation' => [
        'intervenant'    => [
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
        'administration' => [
            'pages' => [
                'intervenants' => [
                    'pages' => [
                        'missions-taux' => [
                            'label'    => "Taux de mission",
                            'route'    => 'missions-taux',
                            'resource' => PrivilegeController::getResourceId(MissionTauxController::class, 'index'),
                            'order'    => 60,
                        ],
                        'missions-type' => [
                            'label'    => "Type de mission",
                            'route'    => 'missions-type',
                            'resource' => PrivilegeController::getResourceId(MissionTauxController::class, 'index'),
                            'order'    => 70,
                        ],
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
            'action'     => ['index', 'liste'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION,
            ],
            'assertion'  => Assertion\MissionAssertion::class,
        ],
        [
            'controller' => MissionController::class,
            'action'     => ['modifier'],
            'privileges' => [
                Privileges::MISSION_EDITION,
            ],
            //'assertion'  => Assertion\MissionAssertion::class,
        ],
        [
            'controller' => MissionController::class,
            'action'     => ['supprimer'],
            'privileges' => [
                Privileges::MISSION_EDITION,
            ],
            //'assertion'  => Assertion\MissionAssertion::class,
        ],
        [
            'controller' => MissionController::class,
            'action'     => ['valider'],
            'privileges' => [
                Privileges::MISSION_VALIDATION,
            ],
            //'assertion'  => Assertion\MissionAssertion::class,
        ],
        [
            'controller' => MissionController::class,
            'action'     => ['devalider'],
            'privileges' => [
                Privileges::MISSION_DEVALIDATION,
            ],
            //'assertion'  => Assertion\MissionAssertion::class,
        ],
        [
            //TODO droit acces
            'controller' => MissionTauxController::class,
            'action'     => ['index', 'supprimer', 'supprimerValeur', 'saisir', 'saisirValeur'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION,
            ],
        ],

        [
            //TODO droit acces
            'controller' => MissionTypeController::class,
            'action'     => ['index', 'supprimer', 'saisir'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION,
            ],
        ],
    ],

    'controllers' => [
        MissionController::class     => Controller\MissionControllerFactory::class,
        MissionTauxController::class => Controller\MissionTauxControllerFactory::class,
        MissionTypeController::class => Controller\MissionTypeControllerFactory::class,
    ],

    'services' => [
        MissionTauxService::class         => MissionTauxServiceFactory::class,
        MissionTypeService::class         => MissionTypeServiceFactory::class,
        Assertion\MissionAssertion::class => AssertionFactory::class,
        Service\MissionService::class     => Service\MissionServiceFactory::class,
    ],

    'forms' => [
        Form\MissionForm::class     => Form\MissionFormFactory::class,
        Form\MissionTauxForm::class => Form\MissionTauxFormFactory::class,
    ],

    'view_helpers' => [
    ],
];