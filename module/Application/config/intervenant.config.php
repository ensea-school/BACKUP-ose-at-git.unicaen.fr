<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use Contrat\Controller\ContratController;
use Paiement\Controller\PaiementController;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'intervenant' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/intervenant',
                    'defaults' => [
                        'controller' => 'Application\Controller\Intervenant',
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'rechercher'            => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/rechercher',
                            'defaults' => [
                                'action' => 'rechercher',
                            ],
                        ],
                    ],
                    'recherche-intervenant' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/recherche-intervenant',
                            'defaults' => [
                                'action' => 'recherche-intervenant',
                            ],
                        ],
                    ],
                    'recherche-json'        => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/recherche-json',
                            'defaults' => [
                                'action' => 'recherche-json',
                            ],
                        ],
                    ],
                    'recherche'             => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/recherche',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],

                    'voir'                => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/voir',
                            'defaults' => [
                                'action' => 'voir',
                            ],
                        ],
                    ],
                    'fiche'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/fiche',
                            'defaults' => [
                                'action' => 'fiche',
                            ],
                        ],
                    ],
                    'creer'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/creer',
                            'defaults' => [
                                'action'        => 'saisir',
                                'action-detail' => 'creer',
                            ],
                        ],
                    ],
                    'saisir'              => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/saisir',
                            'defaults' => [
                                'action'        => 'saisir',
                                'action-detail' => 'saisir',
                            ],
                        ],
                    ],
                    'dupliquer'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/dupliquer',
                            'defaults' => [
                                'action'        => 'saisir',
                                'action-detail' => 'dupliquer',
                            ],
                        ],
                    ],
                    'synchronisation'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/synchronisation',
                            'defaults' => [
                                'action' => 'synchronisation',
                            ],
                        ],
                    ],
                    'synchroniser'        => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/synchroniser',
                            'defaults' => [
                                'action' => 'synchroniser',
                            ],
                        ],
                    ],
                    'supprimer'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/supprimer',
                            'defaults' => [
                                'action' => 'supprimer',
                            ],
                        ],
                    ],
                    'historiser'          => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/historiser',
                            'defaults' => [
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/restaurer',
                            'defaults' => [
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'definir-par-defaut'  => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/definir-par-defaut',
                            'defaults' => [
                                'action' => 'definir-par-defaut',
                            ],
                        ],
                    ],
                    'feuille-de-route'    => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/feuille-de-route',
                            'defaults' => [
                                'action' => 'feuille-de-route',
                            ],
                        ],
                    ],
                    'mise-en-paiement'    => [
                        'type'          => 'Segment',
                        'may_terminate' => false,
                        'options'       => [
                            'route'    => '/:intervenant/mise-en-paiement',
                            'defaults' => [
                                'controller' => PaiementController::class,
                            ],
                        ],
                        'child_routes'  => [
                            'visualisation' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/visualisation',
                                    'defaults' => [
                                        'action' => 'visualisationMiseEnPaiement',
                                    ],
                                ],
                            ],
                            'demande'       => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/demande',
                                    'defaults' => [
                                        'action' => 'demandeMiseEnPaiement',
                                    ],
                                ],
                            ],
                            'edition'       => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/edition',
                                    'defaults' => [
                                        'action' => 'editionMiseEnPaiement',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'contrat'             => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:intervenant/contrat',
                            'defaults' => [
                                'controller' => ContratController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'intervenant' => [
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'index'),
                        'order'    => 1,
                        'pages'    => [
                            'rechercher'       => [
                                'label'        => " Rechercher",
                                'title'        => "Rechercher un intervenant",
                                'route'        => 'intervenant/rechercher',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'icon'         => "fas fa-magnifying-glass",
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'rechercher'),
                                'order'        => 1,
                            ],
                            'voir'             => [
                                'label'        => "Fiche individuelle",
                                'title'        => "Consultation de la fiche de l'intervenant {id}",
                                'route'        => 'intervenant/voir',
                                'paramsInject' => [
                                    'intervenant',
                                ],
                                'withtarget'   => true,
                                'resource'     => PrivilegeController::getResourceId('Application\Controller\Intervenant', 'voir'),
                                'order'        => 2,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['rechercher', 'recherche', 'recherche-json', 'recherche-intervenant'],
                    'privileges' => [
                        Privileges::INTERVENANT_RECHERCHE,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['index', 'voir', 'fiche', 'menu'],
                    'privileges' => [
                        Privileges::INTERVENANT_FICHE,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['saisir', 'definir-par-defaut', 'synchronisation', 'synchroniser'],
                    'privileges' => [
                        Privileges::INTERVENANT_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['restaurer'],
                    'privileges' => [
                        Privileges::INTERVENANT_AJOUT_STATUT,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => ['supprimer', 'historiser'],
                    'privileges' => [
                        Privileges::INTERVENANT_SUPPRESSION,
                    ],
                    'assertion'  => Assertion\IntervenantAssertion::class,
                ],
            ],
        ],

        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
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
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            'Application\Controller\Intervenant' => Controller\Factory\IntervenantControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories'  => [
            Service\IntervenantService::class     => Service\Factory\IntervenantServiceFactory::class,
            Processus\IntervenantProcessus::class => Processus\Factory\IntervenantProcessusFactory::class,
            Assertion\IntervenantAssertion::class => \UnicaenPrivilege\Assertion\AssertionFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'intervenant'       => View\Helper\Intervenant\IntervenantViewHelper::class,
        ],
    ],
    'form_elements'   => [
        'factories'  => [
            Form\Intervenant\EditionForm::class => Form\Intervenant\Factory\EditionFormFactory::class,
        ],
    ],
];
