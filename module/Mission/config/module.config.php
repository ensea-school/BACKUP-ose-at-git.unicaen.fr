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
                'get'       => [
                    'route'      => '/get/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'get',
                ],
                'ajout'     => [
                    'route'      => '/ajout/:intervenant',
                    'controller' => MissionController::class,
                    'action'     => 'ajout',
                ],
                'saisie'    => [
                    'route'      => '/saisie/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'saisie',
                ],
                'supprimer' => [
                    'route'      => '/supprimer/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'supprimer',
                ],
                'valider'   => [
                    'route'      => '/valider/:mission',
                    'controller' => MissionController::class,
                    'action'     => 'valider',
                ],
                'devalider' => [
                    'route'      => '/devalider/:mission',
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
                'saisir'    => [
                    'route'      => '/saisir[/:typeMission]',
                    'controller' => MissionTypeController::class,
                    'action'     => 'saisir',
                ],
                'supprimer' => [
                    'route'      => '/supprimer/:typeMission',
                    'controller' => MissionTypeController::class,
                    'action'     => 'supprimer',
                ],
            ],
        ],
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

    'guards' => [
        [
            'controller' => MissionController::class,
            'action'     => ['index', 'get', 'liste'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION,
            ],
            //'assertion'  => Assertion\MissionAssertion::class,
        ],
        [
            'controller' => MissionController::class,
            'action'     => ['ajout', 'saisie'],
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
        ],
        [
            'controller' => MissionController::class,
            'action'     => ['valider'],
            'privileges' => [
                Privileges::MISSION_VALIDATION,
            ],
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
            'controller' => MissionTauxController::class,
            'action'     => ['index'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION_TAUX,
            ],
        ],
        [
            'controller' => MissionTauxController::class,
            'action'     => ['saisir', 'saisirValeur'],
            'privileges' => [
                Privileges::MISSION_EDITION_TAUX,
            ],
        ],
        [
            'controller' => MissionTauxController::class,
            'action'     => ['supprimer', 'supprimerValeur'],
            'privileges' => [
                Privileges::MISSION_SUPPRESSION_TAUX,
            ],
        ],

        [
            'controller' => MissionTypeController::class,
            'action'     => ['index'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION_TYPE,
            ],
        ],
        [
            'controller' => MissionTypeController::class,
            'action'     => ['saisir'],
            'privileges' => [
                Privileges::MISSION_EDITION_TYPE,
            ],
        ],
        [
            'controller' => MissionTypeController::class,
            'action'     => ['supprimer'],
            'privileges' => [
                Privileges::MISSION_SUPPRESSION_TYPE,
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