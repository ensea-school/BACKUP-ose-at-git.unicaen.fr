<?php

namespace Mission;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Mission\Controller\MissionController;
use Mission\Controller\MissionTypeController;
use Mission\Controller\OffreEmploiController;
use Mission\Controller\OffreEmploiControllerFactory;
use Mission\Service\MissionTypeService;
use Mission\Service\MissionTypeServiceFactory;
use Mission\Service\OffreEmploiService;
use Mission\Service\OffreEmploiServiceFactory;
use UnicaenAuth\Assertion\AssertionFactory;
use UnicaenAuth\Guard\PrivilegeController;


return [
    'routes' => [
        'intervenant'  => [
            'child_routes' => [
                'missions' => [
                    'route'         => '/:intervenant/missions',
                    'controller'    => MissionController::class,
                    'action'        => 'index',
                    'privileges'    => Privileges::MISSION_VISUALISATION,
                    //'assertion'  => Assertion\MissionAssertion::class,
                    'may_terminate' => true,
                    'child_routes'  => [
                        'modification' => [
                            'route'      => '/modification',
                            'controller' => MissionController::class,
                            'action'     => 'modification',
                            'privileges' => Privileges::MISSION_EDITION,
                            //'assertion'  => Assertion\MissionAssertion::class,
                        ],
                    ],
                ],
            ],
        ],
        'mission'      => [
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
                'suivi'          => [
                    'route'      => '/suivi/:intervenant',
                    'controller' => MissionController::class,
                    'action'     => 'suivi',
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
        'offre-emploi' => [
            'route'         => '/offre-emploi',
            'controller'    => OffreEmploiController::class,
            'action'        => 'index',
            'privileges'    => Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'saisir' => [
                    'route'      => '/saisir[/:offreEmploi]',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'saisir',
                ],
                'get'    => [
                    'route'      => '/get/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'get',
                ],
                'liste'  => [
                    'route'      => '/liste',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'liste',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
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
                    'order'               => 8,
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
                'rh'           => [
                    'pages' => [
                        'offre-emploi' => [
                            'label'    => "Offres d'emploi",
                            'route'    => 'offre-emploi',
                            'resource' => PrivilegeController::getResourceId(OffreEmploiController::class, 'index'),
                            'order'    => 80,
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
            'controller' => MissionTypeController::class,
            'action'     => ['index'],
            'privileges' => [
                Privileges::MISSION_VISUALISATION_TYPE,
            ],
        ],
        [
            'controller' => MissionTypeController::class,
            'action'     => ['saisie'],
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
        [
            'controller' => OffreEmploiController::class,
            'action'     => ['index', 'liste', 'saisir'],
            'privileges' => [
                Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
            ],
        ],
    ],

    'controllers' => [
        MissionController::class     => Controller\MissionControllerFactory::class,
        MissionTypeController::class => Controller\MissionTypeControllerFactory::class,
        OffreEmploiController::class => OffreEmploiControllerFactory::class,
    ],

    'services' => [
        MissionTypeService::class         => MissionTypeServiceFactory::class,
        Assertion\MissionAssertion::class => AssertionFactory::class,
        Service\MissionService::class     => Service\MissionServiceFactory::class,
        OffreEmploiService::class         => OffreEmploiServiceFactory::class,
    ],

    'forms' => [
        Form\MissionForm::class     => Form\MissionFormFactory::class,
        Form\OffreEmploiForm::class => Form\OffreEmploiFormFactory::class,
    ],

    'view_helpers' => [
    ],
];