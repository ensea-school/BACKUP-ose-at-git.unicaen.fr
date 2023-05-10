<?php

namespace Mission;


use Application\Provider\Privilege\Privileges;
use Mission\Controller\OffreEmploiController;
use Mission\Controller\OffreEmploiControllerFactory;
use Mission\Service\CandidatureService;
use Mission\Service\CandidatureServiceFactory;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Mission\Service\OffreEmploiService;
use Mission\Service\OffreEmploiServiceFactory;


return [
    'routes' => [
        'offre-emploi' => [
            'route'         => '/offre-emploi',
            'controller'    => OffreEmploiController::class,
            'action'        => 'index',
            'privileges'    => Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'              => [
                    'route'      => '/saisir[/:offreEmploi]',
                    'controller' => OffreEmploiController::class,
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_MODIFIER,
                    'action'     => 'saisir',
                ],
                'detail'              => [
                    'route'      => '/detail[/:offreEmploi]',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'detail',
                ],
                'get'                 => [
                    'route'      => '/get/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'get',
                ],
                'liste'               => [
                    'route'      => '/liste',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'liste',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
                ],
                'supprimer'           => [
                    'route'      => '/supprimer/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'supprimer',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION,
                ],
                'valider'             => [
                    'route'      => '/valider/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'valider',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_VALIDER,
                ],
                'devalider'           => [
                    'route'      => '/devalider/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'devalider',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_VALIDER,
                ],
                'postuler'            => [
                    'route'      => '/postuler/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'postuler',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_POSTULER,
                ],
                'valider-candidature' => [
                    'route'      => '/validation-candidature/:candidature',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'valider-candidature',
                    'privileges' => Privileges::MISSION_CANDIDATURE_VALIDER,
                ],


            ],

        ],


    ],


    'navigation' => [
        'gestion' => [
            'pages' => [
                'offres-emploi' => [
                    'label'    => "Offre emplois étudiants",
                    'icon'     => 'fas fa-duotone fa-pen-to-square',
                    'title'    => "Offre",
                    'route'    => 'offre-emploi',
                    'resource' => PrivilegeController::getResourceId(OffreEmploiController::class, 'index'),
                    'order'    => 60,
                    'color'    => '#217DD8',
                    'pages'    => [
                        'offre' => [
                            'label'    => "Consulter la liste des offres",
                            'icon'     => 'fas fa-duotone fa-pen-to-square',
                            'title'    => "Consulter la liste des emplois étudiants",
                            'route'    => 'offre-emploi',
                            'resource' => PrivilegeController::getResourceId(OffreEmploiController::class, 'index'),
                            'order'    => 10,
                            'color'    => '#217DD8',
                        ],
                    ],
                ],
            ],
        ],


    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::MISSION_OFFRE_EMPLOI_MODIFIER,
                Privileges::MISSION_OFFRE_EMPLOI_VALIDER,
                Privileges::MISSION_OFFRE_EMPLOI_POSTULER,
                Privileges::MISSION_CANDIDATURE_VISUALISATION,
                Privileges::MISSION_CANDIDATURE_VALIDER,

            ],
            'resources'  => 'OffreEmploi',
            'assertion'  => Assertion\OffreEmploiAssertion::class,
        ],

    ],


    'guards' => [
        [
            'controller' => OffreEmploiController::class,
            'action'     => ['saisir'],
            'privileges' => [
                Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
            ],
        ],
        [
            'controller' => OffreEmploiController::class,
            'action'     => ['supprimer'],
            'privileges' => [
                Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION,
            ],
        ],
        [
            'controller' => OffreEmploiController::class,
            'action'     => ['valider', 'devalider'],
            'privileges' => [
                Privileges::MISSION_OFFRE_EMPLOI_VALIDER,
            ],
        ],
        [
            'controller' => OffreEmploiController::class,
            'action'     => ['valider-candidature'],
            'privileges' => [
                Privileges::MISSION_CANDIDATURE_VALIDER,
            ],
        ],
        [
            'controller' => OffreEmploiController::class,
            'action'     => ['postuler'],
            'privileges' => [
                Privileges::MISSION_OFFRE_EMPLOI_POSTULER,
            ],
        ],
        [
            'controller' => OffreEmploiController::class,
            'action'     => ['index', 'detail', 'list', 'get'],
            'roles'      => ['guest'],

        ],
    ],

    'controllers' => [
        OffreEmploiController::class => OffreEmploiControllerFactory::class,
    ],

    'services' => [
        OffreEmploiService::class             => OffreEmploiServiceFactory::class,
        CandidatureService::class             => CandidatureServiceFactory::class,
        Assertion\OffreEmploiAssertion::class => AssertionFactory::class,

    ],

    'forms' => [
        Form\OffreEmploiForm::class => Form\OffreEmploiFormFactory::class,
    ],

    'view_helpers' => [
    ],
];