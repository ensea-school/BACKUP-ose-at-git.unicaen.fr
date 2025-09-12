<?php

namespace Enseignement;

use Application\Provider\Privilege\Privileges;
use Enseignement\Controller\EnseignementController;
use Enseignement\Controller\VolumeHoraireController;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Service\Controller\ServiceController;
use UnicaenPrivilege\Assertion\AssertionFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Workflow\Entity\Db\WfEtape;
use Workflow\Entity\Db\WorkflowEtape;


return [
    'routes' => [
        'intervenant'  => [
            'child_routes' => [
                'enseignement-prevu'   => [
                    'route'      => '/:intervenant/enseignement-prevu',
                    'controller' => EnseignementController::class,
                    'action'     => 'prevu',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'PREVU',
                    ],
                ],
                'enseignement-realise' => [
                    'route'      => '/:intervenant/enseignement-realise',
                    'controller' => EnseignementController::class,
                    'action'     => 'realise',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'REALISE',
                    ],
                ],
                'validation'           => [
                    'route'         => '/:intervenant/validation',
                    'may_terminate' => false,
                    'child_routes'  => [
                        'enseignement' => [
                            'route'         => '/enseignement',
                            'controller'    => EnseignementController::class,
                            'may_terminate' => false,
                            'child_routes'  => [
                                'prevu'     => [
                                    'route'  => '/prevu',
                                    'action' => 'validation-prevu',
                                ],
                                'realise'   => [
                                    'route'  => '/realise',
                                    'action' => 'validation-realise',
                                ],
                                'valider'   => [
                                    'route'       => '/valider/:typeVolumeHoraire/:structure',
                                    'action'      => 'valider',
                                    'constraints' => [
                                        'typeVolumeHoraire' => '[0-9]*',
                                        'structure'         => '[0-9]*',
                                    ],
                                ],
                                'devalider' => [
                                    'route'       => '/devalider/:validation',
                                    'action'      => 'devalider',
                                    'constraints' => [
                                        'validation' => '[0-9]*',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'enseignement' => [
            'route'        => '/enseignement',
            'controller'   => EnseignementController::class,
            'child_routes' => [
                /*      'prevu'   => [
                          'route'      => 'prevu',
                          'controller' => EnseignementController::class,
                          'action'     => 'prevu',
                          'defaults'   => [
                              'type-volume-horaire-code' => 'PREVU',
                          ],
                      ],
                      'realise' => [
                          'route'      => 'realise',
                          'controller' => EnseignementController::class,
                          'action'     => 'realise',
                          'defaults'   => [
                              'type-volume-horaire-code' => 'REALISE',
                          ],
                      ],*/
                'saisie'                 => [
                    'route'       => '/saisie/:type-volume-horaire-code[/:service]',
                    'action'      => 'saisie',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],

                ],
                'rafraichir-ligne'       => [
                    'route'       => '/rafraichir-ligne/:service',
                    'action'      => 'rafraichir-ligne',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                ],
                'saisie-form-refresh-vh' => [
                    'route'       => '/saisie-form-refresh-vh[/:service]',
                    'action'      => 'saisie-form-refresh-vh',
                    'constraints' => [
                        'id'      => '[0-9]*',
                        'service' => '[0-9]*',
                    ],
                ],
                'suppression'            => [
                    'route'       => '/suppression/:service',
                    'action'      => 'suppression',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                ],
                'initialisation'         => [
                    'route'  => '/initialisation/:intervenant',
                    'action' => 'initialisation',
                ],
                'constatation'           => [
                    'route'  => '/constatation',
                    'action' => 'constatation',
                ],
            ],
        ],

        'volume-horaire' => [
            'route'         => '/volume-horaire',
            'controller'    => VolumeHoraireController::class,
            'action'        => 'index',
            'may_terminate' => true,
            'child_routes'  => [
                'liste'                  => [
                    'route'       => '/liste[/:service]',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'action'      => 'liste',
                ],
                'saisie'                 => [
                    'route'       => '/saisie/:service',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'action'      => 'saisie',
                ],
                'saisie-calendaire'      => [
                    'route'       => '/saisie-calendaire/:service',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'action'      => 'saisie-calendaire',
                ],
                'suppression-calendaire' => [
                    'route'       => '/suppression-calendaire/:service',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'action'      => 'suppression-calendaire',
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'pages' => [
                /*'enseignements-prevus'   => [
                    'label'               => "Enseignements prévisionnels",
                    'title'               => "Enseignements prévisionnels de l'intervenant",
                    'route'               => 'intervenant/enseignement-prevu',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WorkflowEtape::ENSEIGNEMENT_SAISIE,
                    'withtarget'          => true,
                    'resource'            => PrivilegeController::getResourceId(ServiceController::class, 'intervenant-saisie-prevu'),
                    'visible'             => Assertion\EnseignementAssertion::class,
                    'order'               => 6,
                ],*/
                'validation-enseignement-prevu'   => [
                    'label'               => "Validation des enseignements prévisionnels",
                    'title'               => "Validation des enseignements prévisionnels de l'intervenant",
                    'route'               => 'intervenant/validation/enseignement/prevu',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WorkflowEtape::ENSEIGNEMENT_VALIDATION,
                    'withtarget'          => true,
                    'visible'             => Assertion\EnseignementAssertion::class,
                    'order'               => 8,
                ],

                /*'enseignements-realises' => [
                    'label'               => "Enseignements réalisés",
                    'title'               => "Constatation des enseignements réalisés",
                    'route'               => 'intervenant/enseignement-realise',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WorkflowEtape::ENSEIGNEMENT_SAISIE_REALISE,
                    'withtarget'          => true,
                    'resource'            => PrivilegeController::getResourceId(ServiceController::class, 'intervenant-saisie-realise'),
                    'visible'             => Assertion\EnseignementAssertion::class,
                    'order'               => 13,
                ],*/
                'validation-enseignement-realise' => [
                    'label'               => "Validation des enseignements réalisés",
                    'title'               => "Validation des enseignements réalisés de l'intervenant",
                    'route'               => 'intervenant/validation/enseignement/realise',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WorkflowEtape::ENSEIGNEMENT_VALIDATION_REALISE,
                    'withtarget'          => true,
                    'visible'             => Assertion\EnseignementAssertion::class,
                    'order'               => 14,
                ],
            ],
        ],
    ],

    'rules' => [
        [
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
            ],
            'resources'  => ['Service', 'Intervenant'],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VALIDATION,
                Privileges::ENSEIGNEMENT_REALISE_VALIDATION,
                Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION,
                Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION,
            ],
            'resources'  => ['Service', 'VolumeHoraire', 'Validation'],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => Privileges::ENSEIGNEMENT_DEVALIDATION,
            'resources'  => 'Validation',
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::ENSEIGNEMENT_EXTERIEUR,
            ],
            'resources'  => ['Intervenant', 'Service'],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::MOTIF_NON_PAIEMENT_VISUALISATION,
                Privileges::MOTIF_NON_PAIEMENT_EDITION,
                Privileges::TAG_EDITION,
                Privileges::TAG_VISUALISATION,
            ],
            'resources'  => 'Intervenant',
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => EnseignementController::class,
            'action'     => ['prevu'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
            ],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['realise'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['saisie', 'rafraichir-ligne', 'saisie-form-refresh-vh', 'suppression', 'initialisation', 'constatation'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_EDITION,
            ],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],

        [
            'controller' => VolumeHoraireController::class,
            'action'     => ['liste'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
        ],
        [
            'controller' => VolumeHoraireController::class,
            'action'     => ['saisie', 'saisie-calendaire', 'suppression-calendaire'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
            ],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['validation-prevu'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
            ],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['validation-realise'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['valider'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VALIDATION,
                Privileges::ENSEIGNEMENT_REALISE_VALIDATION,
            ],
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['devalider'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_DEVALIDATION,
            ],
        ],

    ],


    'controllers' => [
        EnseignementController::class  => InvokableFactory::class,
        VolumeHoraireController::class => InvokableFactory::class,
    ],


    'services' => [
        Processus\EnseignementProcessus::class           => InvokableFactory::class,
        Processus\ValidationEnseignementProcessus::class => InvokableFactory::class,
        Service\ServiceService::class                    => InvokableFactory::class,
        Service\VolumeHoraireService::class              => InvokableFactory::class,
        Assertion\EnseignementAssertion::class           => AssertionFactory::class,
    ],


    'forms' => [
        Form\EnseignementSaisieForm::class              => InvokableFactory::class,
        Form\EnseignementSaisieFieldset::class          => Form\EnseignementSaisieFieldsetFactory::class,
        Form\VolumeHoraireSaisieForm::class             => InvokableFactory::class,
        Form\VolumeHoraireSaisieCalendaireForm::class   => InvokableFactory::class,
        Form\VolumeHoraireSaisieMultipleFieldset::class => InvokableFactory::class,
    ],


    'view_helpers' => [
        'enseignements'                => View\Helper\EnseignementsFactory::class,
        'ligneEnseignement'            => View\Helper\LigneEnseignementFactory::class,
        'enseignementSaisieForm'       => View\Helper\EnseignementSaisieFormFactory::class,
        'volumeHoraireListe'           => View\Helper\VolumeHoraire\ListeFactory::class,
        'volumeHoraireListeCalendaire' => View\Helper\VolumeHoraire\ListeCalendaireFactory::class,
    ],
];