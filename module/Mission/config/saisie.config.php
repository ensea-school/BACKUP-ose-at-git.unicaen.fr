<?php

namespace Mission;

use Application\Provider\Privileges;
use Framework\Authorize\Authorize;


return [
    'routes' => [
        'intervenant' => [
            'child_routes' => [
                'missions' => [
                    'route' => '/:intervenant/missions',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'index',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
            ],
        ],
        'mission' => [
            'route' => '/mission',
            'may_terminate' => false,
            'child_routes' => [
                'liste' => [
                    'route' => '/liste/:intervenant',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'liste',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
                'get' => [
                    'route' => '/get/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'get',
                    'privileges' => Privileges::MISSION_VISUALISATION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
                'ajout' => [
                    'route' => '/ajout/:intervenant',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'ajout',
                    'privileges' => Privileges::MISSION_EDITION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
                'saisie' => [
                    'route' => '/saisie/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'saisie',
                    'privileges' => Privileges::MISSION_EDITION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
                'supprimer' => [
                    'route' => '/supprimer/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'supprimer',
                    'privileges' => Privileges::MISSION_EDITION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
                'valider' => [
                    'route' => '/valider/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'valider',
                    'privileges' => Privileges::MISSION_VALIDATION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
                'devalider' => [
                    'route' => '/devalider/:mission',
                    'controller' => Controller\SaisieController::class,
                    'action' => 'devalider',
                    'privileges' => Privileges::MISSION_DEVALIDATION,
                    'assertion'  => Assertion\SaisieAssertion::class,
                ],
                'volume-horaire' => [
                    'route' => '/volume-horaire',
                    'controller' => Controller\SaisieController::class,
                    'may_terminate' => false,
                    'child_routes' => [
                        'supprimer' => [
                            'route' => '/supprimer/:volumeHoraireMission',
                            'controller' => Controller\SaisieController::class,
                            'action' => 'volume-horaire-supprimer',
                            'privileges' => Privileges::MISSION_EDITION,
                            'assertion'  => Assertion\SaisieAssertion::class,
                        ],
                        'valider' => [
                            'route' => '/valider/:volumeHoraireMission',
                            'controller' => Controller\SaisieController::class,
                            'action' => 'volume-horaire-valider',
                            'privileges' => Privileges::MISSION_VALIDATION,
                            'assertion'  => Assertion\SaisieAssertion::class,
                        ],
                        'devalider' => [
                            'route' => '/devalider/:volumeHoraireMission',
                            'controller' => Controller\SaisieController::class,
                            'action' => 'volume-horaire-devalider',
                            'privileges' => Privileges::MISSION_DEVALIDATION,
                            'assertion'  => Assertion\SaisieAssertion::class,
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
                    'label' => "Missions",
                    'title' => "Missions",
                    'route' => 'intervenant/missions',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget' => true,
                    'resource' => Authorize::controllerResource(Controller\SaisieController::class, 'index'),
                    'order' => 8,
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

    'rules' => [
        [
            'privileges' => [
                Assertion\SaisieAssertion::CAN_ADD_HEURES,
                Privileges::MISSION_EDITION,
                Privileges::MISSION_VALIDATION,
                Privileges::MISSION_DEVALIDATION
            ],
            'resources' => 'Mission',
            'assertion' => Assertion\SaisieAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::MISSION_EDITION,
                Privileges::MISSION_VALIDATION,
                Privileges::MISSION_DEVALIDATION
            ],
            'resources' => 'VolumeHoraireMission',
            'assertion' => Assertion\SaisieAssertion::class,
        ],
    ],
];