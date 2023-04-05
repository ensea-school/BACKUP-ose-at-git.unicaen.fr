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
                'missions' => [
                    'route'      => '/:intervenant/missions',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'index',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
            ],
        ],
        'mission'     => [
            'route'         => '/mission',
            'may_terminate' => false,
            'child_routes'  => [
                'liste'          => [
                    'route'      => '/liste/:intervenant',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'liste',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'get'            => [
                    'route'      => '/get/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'get',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'ajout'          => [
                    'route'      => '/ajout/:intervenant',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'ajout',
                    'privileges' => Privileges::MISSION_EDITION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'saisie'         => [
                    'route'      => '/saisie/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'saisie',
                    'privileges' => Privileges::MISSION_EDITION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                ],
                'supprimer'      => [
                    'route'      => '/supprimer/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'supprimer',
                    'privileges' => Privileges::MISSION_EDITION,
                ],
                'valider'        => [
                    'route'      => '/valider/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'valider',
                    'privileges' => Privileges::MISSION_VALIDATION,
                ],
                'devalider'      => [
                    'route'      => '/devalider/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action'     => 'devalider',
                    'privileges' => Privileges::MISSION_DEVALIDATION,
                ],
                'volume-horaire' => [
                    'route'         => '/volume-horaire',
                    'controller'    => Controller\SaisieController::class,
                    'may_terminate' => false,
                    'child_routes'  => [
                        'supprimer' => [
                            'route'      => '/supprimer/:volumeHoraireMission',
                            'controller' => Controller\SaisieController::class,
                            'action'     => 'volume-horaire-supprimer',
                            'privileges' => Privileges::MISSION_EDITION,
                        ],
                        'valider'   => [
                            'route'      => '/valider/:volumeHoraireMission',
                            'controller' => Controller\SaisieController::class,
                            'action'     => 'volume-horaire-valider',
                            'privileges' => Privileges::MISSION_VALIDATION,
                        ],
                        'devalider' => [
                            'route'      => '/devalider/:volumeHoraireMission',
                            'controller' => Controller\SaisieController::class,
                            'action'     => 'volume-horaire-devalider',
                            'privileges' => Privileges::MISSION_DEVALIDATION,
                        ],
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
                    'order'               => 8,
                ],
            ],
        ],
    ],

    'controllers' => [
        Controller\SaisieController::class => Controller\SaisieControllerFactory::class,
    ],

    'forms' => [
        Form\MissionForm::class => Form\MissionFormFactory::class,
    ],
];