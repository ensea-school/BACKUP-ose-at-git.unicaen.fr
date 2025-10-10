<?php

namespace Intervenant;

use Application\Provider\Privileges;


return [
    'routes' => [
        'intervenant' => [
            'route'         => '/intervenant',
            'controller'    => Controller\IntervenantController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'rechercher'            => [
                    'route'      => '/rechercher',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'rechercher',
                ],
                'recherche-intervenant' => [
                    'route'      => '/recherche-intervenant',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'recherche-intervenant',
                ],
                'recherche-json'        => [
                    'route'      => '/recherche-json',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'recherche-json',
                ],
                'recherche'             => [
                    'route'      => '/recherche',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'recherche',
                ],

                'voir'               => [
                    'route'      => '/:intervenant/voir',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'voir',
                ],
                'fiche'              => [
                    'route'      => '/:intervenant/fiche',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'fiche',
                ],
                'creer'              => [
                    'route'      => '/creer',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'saisir',
                    'defaults'   => [
                        'action-detail' => 'creer',
                    ],
                ],
                'saisir'             => [
                    'route'      => '/:intervenant/saisir',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'saisir',
                    'defaults'   => [
                        'action-detail' => 'saisir',
                    ],
                ],
                'dupliquer'          => [
                    'route'      => '/:intervenant/dupliquer',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'saisir',
                    'defaults'   => [
                        'action-detail' => 'dupliquer',
                    ],
                ],
                'synchronisation'    => [
                    'route'      => '/:intervenant/synchronisation',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'synchronisation',
                ],
                'synchroniser'       => [
                    'route'      => '/:intervenant/synchroniser',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'synchroniser',
                ],
                'supprimer'          => [
                    'route'      => '/:intervenant/supprimer',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'supprimer',
                ],
                'historiser'         => [
                    'route'      => '/:intervenant/historiser',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'historiser',
                ],
                'restaurer'          => [
                    'route'      => '/:intervenant/restaurer',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'restaurer',
                ],
                'definir-par-defaut' => [
                    'route'      => '/:intervenant/definir-par-defaut',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'definir-par-defaut',
                ],
                'feuille-de-route'   => [
                    'route'      => '/:intervenant/feuille-de-route',
                    'controller' => Controller\IntervenantController::class,
                    'action'     => 'feuille-de-route',
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'label'    => 'Intervenant',
            'title'    => "Intervenant",
            'route'    => 'intervenant',
            'order'    => 1,
            'pages'    => [
                'rechercher' => [
                    'label'        => " Rechercher",
                    'title'        => "Rechercher un intervenant",
                    'route'        => 'intervenant/rechercher',
                    'icon'         => "fas fa-magnifying-glass",
                    'order'        => 1,
                ],
                'voir'       => [
                    'label'        => "Fiche individuelle",
                    'title'        => "Consultation de la fiche de l'intervenant {id}",
                    'route'        => 'intervenant/voir',
                    'order'        => 2,
                ],
            ],
        ],
    ],


    'rules' => [
        [
            'privileges' => [
                Privileges::INTERVENANT_EDITION,
                Privileges::INTERVENANT_SUPPRESSION,
                Privileges::INTERVENANT_EDITION_AVANCEE,
                Privileges::MISSION_OFFRE_EMPLOI_POSTULER,
            ],
            'resources'  => ['Intervenant'],
            'assertion'  => Assertion\IntervenantAssertion::class,
        ],
    ],


    'guards' => [
        [
            'controller' => Controller\IntervenantController::class,
            'action'     => ['rechercher', 'recherche', 'recherche-json', 'recherche-intervenant'],
            'privileges' => [
                Privileges::INTERVENANT_RECHERCHE,
            ],
        ],
        [
            'controller' => Controller\IntervenantController::class,
            'action'     => ['index', 'voir', 'fiche', 'menu'],
            'privileges' => [
                Privileges::INTERVENANT_FICHE,
            ],
        ],
        [
            'controller' => Controller\IntervenantController::class,
            'action'     => ['saisir', 'definir-par-defaut', 'synchronisation', 'synchroniser'],
            'privileges' => [
                Privileges::INTERVENANT_EDITION,
            ],
        ],
        [
            'controller' => Controller\IntervenantController::class,
            'action'     => ['restaurer'],
            'privileges' => [
                Privileges::INTERVENANT_AJOUT_STATUT,
            ],
        ],
        [
            'controller' => Controller\IntervenantController::class,
            'action'     => ['supprimer', 'historiser'],
            'privileges' => [
                Privileges::INTERVENANT_SUPPRESSION,
            ],
            'assertion'  => Assertion\IntervenantAssertion::class,
        ],
    ],


    'controllers' => [
        Controller\IntervenantController::class => Controller\IntervenantControllerFactory::class,
    ],


    'services' => [
        Service\IntervenantService::class     => Service\IntervenantServiceFactory::class,
        Processus\IntervenantProcessus::class => Processus\IntervenantProcessusFactory::class,
        Service\SituationMatrimonialeService::class   => Service\SituationMatrimonialeServiceFactory::class,
        Command\CalculFeuilleDeRouteCommand::class    => Command\CalculFeuilleDeRouteCommandFactory::class,
    ],


    'view_helpers' => [
        'intervenant' => View\Helper\IntervenantViewHelperFactory::class,
    ],


    'forms' => [
        Form\EditionForm::class => Form\EditionFormFactory::class,
    ],

    'laminas-cli' => [
        'commands' => [
            'calcul-feuille-de-route' => Command\CalculFeuilleDeRouteCommand::class
        ],
    ],
];