<?php

namespace Mission;


use Application\Provider\Privileges;
use Framework\Authorize\AssertionFactory;
use Framework\Authorize\Authorize;
use Mission\Controller\OffreEmploiController;
use Mission\Controller\OffreEmploiControllerFactory;
use Mission\Service\CandidatureService;
use Mission\Service\CandidatureServiceFactory;
use Mission\Service\OffreEmploiService;
use Mission\Service\OffreEmploiServiceFactory;


return [
    'routes' => [
        'intervenant' => [
            'child_routes' => [
                'candidature'      => [
                    'route'      => '/:intervenant/candidature',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'candidature',
                    'privileges' => [Privileges::MISSION_CANDIDATURE_VISUALISATION],
                    'assertion'  => Assertion\OffreEmploiAssertion::class,

                ],
                'get-candidatures' => [
                    'route'      => '/:intervenant/get-candidatures',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'get-candidatures',
                    'privileges' => [Privileges::MISSION_CANDIDATURE_VISUALISATION],
                    'assertion'  => Assertion\OffreEmploiAssertion::class,
                ],
            ],
        ],

        'offre-emploi' => [
            'route'         => '/offre-emploi',
            'controller'    => OffreEmploiController::class,
            'action'        => 'index',
            'privileges'    => 'guest',//Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
            'may_terminate' => true,
            'child_routes'  => [
                'saisir'               => [
                    'route'      => '/saisir[/:offreEmploi]',
                    'controller' => OffreEmploiController::class,
                    'privileges' => [Privileges::MISSION_OFFRE_EMPLOI_MODIFIER, Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION],
                    'action'     => 'saisir',
                ],
                'detail'               => [
                    'route'      => '/detail[/:offreEmploi]',
                    'controller' => OffreEmploiController::class,
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
                    'action'     => 'detail',
                ],
                'get'                  => [
                    'route'      => '/get/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'get',
                    'privilege'  => 'guest',
                ],
                'liste'                => [
                    'route'      => '/liste',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'liste',
                    'privilege'  => 'guest',
                ],
                'supprimer'            => [
                    'route'      => '/supprimer/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'supprimer',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION,
                ],
                'valider'              => [
                    'route'      => '/valider/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'valider',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_VALIDER,
                ],
                'devalider'            => [
                    'route'      => '/devalider/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'devalider',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_VALIDER,
                ],
                'postuler'             => [
                    'route'      => '/postuler/:offreEmploi',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'postuler',
                    'privileges' => Privileges::MISSION_OFFRE_EMPLOI_POSTULER,
                ],
                'accepter-candidature' => [
                    'route'      => '/accepter-candidature/:candidature',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'accepter-candidature',
                    'privileges' => Privileges::MISSION_CANDIDATURE_VALIDER,
                    'assertion'  => Assertion\OffreEmploiAssertion::class,
                ],
                'refuser-candidature'  => [
                    'route'      => '/refuser-candidature/:candidature',
                    'controller' => OffreEmploiController::class,
                    'action'     => 'refuser-candidature',
                    'privileges' => Privileges::MISSION_CANDIDATURE_REFUSER,
                    'assertion'  => Assertion\OffreEmploiAssertion::class,
                ],
            ],
        ],
    ],


    'navigation' => [
        'intervenant'   => [
            'pages' => [
                'candidature' => [
                    'label'        => "Candidatures",
                    'title'        => "Liste de vos candidatures en cours",
                    'route'        => 'intervenant/candidature',
                    'paramsInject' => [
                        'intervenant',
                    ],
                    'withtarget'   => true,
                    'resource'     => Authorize::controllerResource(OffreEmploiController::class, 'candidature'),
                    'order'        => 5,
                ],
            ],
        ],
        'gestion'       => [
            'pages' => [
                'offres-emploi' => [
                    'label'    => "Offre emplois étudiants",
                    'icon'     => 'fas fa-duotone fa-pen-to-square',
                    'title'    => "Offre",
                    'route'    => 'offre-emploi',
                    'resource' => Privileges::getResourceId(Privileges::MISSION_OFFRE_EMPLOI_AJOUTER),
                    'order'    => 60,
                    'color'    => '#217DD8',
                    'pages'    => [
                        'offre' => [
                            'label'    => "Consulter la liste des offres",
                            'title'    => "Consulter la liste des emplois étudiants",
                            'route'    => 'offre-emploi',
                            'resource' => Privileges::getResourceId(Privileges::MISSION_OFFRE_EMPLOI_AJOUTER),
                            'order'    => 10,
                            'color'    => '#217DD8',
                        ],
                    ],
                ],
            ],
        ],
        'offres-emploi' => [
            'label'   => "Offres d'emploi",
            'icon'    => 'fas fa-duotone fa-pen-to-square',
            'title'   => "Consulter la liste des emplois étudiants",
            'route'   => 'offre-emploi',
            'visible' => Assertion\OffreEmploiAssertion::class,
            'order'   => 10,
            'color'   => '#217DD8',
        ],
    ],


    'rules' => [
        [
            'privileges' => [
                Privileges::MISSION_OFFRE_EMPLOI_MODIFIER,
                Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION,
                Privileges::MISSION_OFFRE_EMPLOI_VALIDER,
                Privileges::MISSION_OFFRE_EMPLOI_POSTULER,
                Privileges::MISSION_CANDIDATURE_VISUALISATION,
                Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION,

            ],
            'resources'  => 'OffreEmploi',
            'assertion'  => Assertion\OffreEmploiAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::MISSION_CANDIDATURE_VALIDER,
                Privileges::MISSION_CANDIDATURE_REFUSER,
            ],
            'resources'  => 'Candidature',
            'assertion'  => Assertion\OffreEmploiAssertion::class,
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