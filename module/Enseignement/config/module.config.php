<?php

namespace Enseignement;

use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Enseignement\Controller\EnseignementController;
use Enseignement\Controller\VolumeHoraireController;
use Laminas\ServiceManager\Factory\InvokableFactory;


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
                                    'route'    => '/prevu',
                                    'action'   => 'validation',
                                    'defaults' => [
                                        'type-volume-horaire-code' => 'PREVU',
                                    ],
                                ],
                                'realise'   => [
                                    'route'    => '/realise',
                                    'action'   => 'validation',
                                    'defaults' => [
                                        'type-volume-horaire-code' => 'REALISE',
                                    ],
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
                    'route'       => 'saisie-form-refresh-vh[/:service]',
                    'action'      => 'saisie-form-refresh-vh',
                    'constraints' => [
                        'id' => '[0-9]*',
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
                'validation-enseignement-prevu'   => [
                    'label'               => "Validation des enseignements prévisionnels",
                    'title'               => "Validation des enseignements prévisionnels de l'intervenant",
                    'route'               => 'intervenant/validation/enseignement/prevu',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_SERVICE_VALIDATION,
                    'withtarget'          => true,
                    //   'visible'             => Assertion\ServiceAssertion::class,
                    'order'               => 8,
                ],
                'validation-enseignement-realise' => [
                    'label'               => "Validation des enseignements réalisés",
                    'title'               => "Validation des enseignements réalisés de l'intervenant",
                    'route'               => 'intervenant/validation/enseignement/realise',
                    'paramsInject'        => [
                        'intervenant',
                    ],
                    'workflow-etape-code' => WfEtape::CODE_SERVICE_VALIDATION_REALISE,
                    'withtarget'          => true,
                    //   'visible'             => Assertion\ServiceAssertion::class,
                    'order'               => 14,
                ],
            ],
        ],
    ],

    'rules' => [

    ],

    'guards' => [
        [
            'controller' => EnseignementController::class,
            'action'     => ['prevu'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
            ],
            //'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['realise'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
            //'assertion'  => Assertion\ServiceAssertion::class,
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
            //  'assertion'  => Assertion\ServiceAssertion::class,
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
            // 'assertion'  => Assertion\ServiceAssertion::class,
        ],
        [
            'controller' => EnseignementController::class,
            'action'     => ['validation'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
            ],
            //            'assertion'  => Assertion\ServiceAssertion::class,
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