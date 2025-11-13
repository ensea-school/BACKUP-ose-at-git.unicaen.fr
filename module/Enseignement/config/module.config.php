<?php

namespace Enseignement;

use Application\Provider\Privileges;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Enseignement\Service\ServiceService;
use Enseignement\Service\VolumeHoraireService;
use Intervenant\Entity\Db\Intervenant;
use Service\Assertion\ServiceAssertion;
use Service\Entity\Db\TypeVolumeHoraire;
use Enseignement\Controller\EnseignementController;
use Enseignement\Controller\VolumeHoraireController;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Workflow\Entity\Db\Validation;


return [
    'routes' => [
        'intervenant'  => [
            'child_routes' => [
                'enseignement-prevu'   => [
                    'route'      => '/:intervenant/enseignement-prevu',
                    'controller' => EnseignementController::class,
                    'action'     => 'prevu',
                    'privileges' => Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                    'assertion'  => Assertion\EnseignementAssertion::class,
                    'defaults'   => [
                        'type-volume-horaire-code' => TypeVolumeHoraire::CODE_PREVU,
                    ],
                ],
                'enseignement-realise' => [
                    'route'      => '/:intervenant/enseignement-realise',
                    'controller' => EnseignementController::class,
                    'action'     => 'realise',
                    'privileges' => Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                    'assertion'  => Assertion\EnseignementAssertion::class,
                    'defaults'   => [
                        'type-volume-horaire-code' => TypeVolumeHoraire::CODE_REALISE,
                    ],
                ],
                'validation'           => [
                    'route'         => '/:intervenant/validation',
                    'may_terminate' => false,
                    'child_routes'  => [
                        'enseignement' => [
                            'route'         => '/enseignement',
                            'may_terminate' => false,
                            'child_routes'  => [
                                'prevu'     => [
                                    'route'      => '/prevu',
                                    'controller' => EnseignementController::class,
                                    'action'     => 'validation-prevu',
                                    'privileges' => [
                                        Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                                        Privileges::ENSEIGNEMENT_PREVU_VALIDATION,
                                    ],
                                    'assertion'  => Assertion\EnseignementAssertion::class,
                                ],
                                'realise'   => [
                                    'route'      => '/realise',
                                    'controller' => EnseignementController::class,
                                    'action'     => 'validation-realise',
                                    'privileges' => [
                                        Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                                        Privileges::ENSEIGNEMENT_REALISE_VALIDATION,
                                    ],
                                    'assertion'  => Assertion\EnseignementAssertion::class,
                                ],
                                'valider'   => [
                                    'route'       => '/valider/:typeVolumeHoraire/:structure',
                                    'controller'  => EnseignementController::class,
                                    'action'      => 'valider',
                                    'privileges'  => [
                                        Privileges::ENSEIGNEMENT_PREVU_VALIDATION,
                                        Privileges::ENSEIGNEMENT_REALISE_VALIDATION,
                                    ],
                                    'constraints' => [
                                        'typeVolumeHoraire' => '[0-9]*',
                                        'structure'         => '[0-9]*',
                                    ],
                                ],
                                'devalider' => [
                                    'route'       => '/devalider/:validation',
                                    'controller'  => EnseignementController::class,
                                    'action'      => 'devalider',
                                    'privileges'  => Privileges::ENSEIGNEMENT_DEVALIDATION,
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
                'prevu'                  => [
                    'route'      => 'prevu',
                    'controller' => EnseignementController::class,
                    'action'     => 'prevu',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'PREVU',
                    ],
                ],
                'realise'                => [
                    'route'      => 'realise',
                    'controller' => EnseignementController::class,
                    'action'     => 'realise',
                    'defaults'   => [
                        'type-volume-horaire-code' => 'REALISE',
                    ],
                ],
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
            'may_terminate' => false,
            'child_routes'  => [
                'liste'                  => [
                    'route'       => '/liste[/:service]',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'controller'  => VolumeHoraireController::class,
                    'action'      => 'liste',
                    'privileges'  => [
                        Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                        Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                    ],
                ],
                'saisie'                 => [
                    'route'       => '/saisie/:service',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'controller'  => VolumeHoraireController::class,
                    'action'      => 'saisie',
                    'privileges'  => [
                        Privileges::ENSEIGNEMENT_PREVU_EDITION,
                        Privileges::ENSEIGNEMENT_REALISE_EDITION,
                    ],
                    'assertion'   => Assertion\EnseignementAssertion::class,
                ],
                'saisie-calendaire'      => [
                    'route'       => '/saisie-calendaire/:service',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'controller'  => VolumeHoraireController::class,
                    'action'      => 'saisie-calendaire',
                    'privileges'  => [
                        Privileges::ENSEIGNEMENT_PREVU_EDITION,
                        Privileges::ENSEIGNEMENT_REALISE_EDITION,
                    ],
                    'assertion'   => Assertion\EnseignementAssertion::class,
                ],
                'suppression-calendaire' => [
                    'route'       => '/suppression-calendaire/:service',
                    'constraints' => [
                        'service' => '[0-9]*',
                    ],
                    'controller'  => VolumeHoraireController::class,
                    'action'      => 'suppression-calendaire',
                    'privileges'  => [
                        Privileges::ENSEIGNEMENT_PREVU_EDITION,
                        Privileges::ENSEIGNEMENT_REALISE_EDITION,
                    ],
                    'assertion'   => Assertion\EnseignementAssertion::class,
                ],
            ],
        ],
    ],

    'navigation' => [
        'intervenant' => [
            'pages' => [
                'enseignements-prevus'          => [
                    'label' => "Enseignements prévisionnels",
                    'title' => "Enseignements prévisionnels de l'intervenant",
                    'route' => 'intervenant/enseignement-prevu',
                    'order' => 6,
                ],
                'validation-enseignement-prevu' => [
                    'label' => "Validation des enseignements prévisionnels",
                    'title' => "Validation des enseignements prévisionnels de l'intervenant",
                    'route' => 'intervenant/validation/enseignement/prevu',
                    'order' => 8,
                ],

                'enseignements-realises'          => [
                    'label' => "Enseignements réalisés",
                    'title' => "Constatation des enseignements réalisés",
                    'route' => 'intervenant/enseignement-realise',
                    'order' => 13,
                ],
                'validation-enseignement-realise' => [
                    'label' => "Validation des enseignements réalisés",
                    'title' => "Validation des enseignements réalisés de l'intervenant",
                    'route' => 'intervenant/validation/enseignement/realise',
                    'order' => 14,
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
            'resources'  => [Service::class,
                             Intervenant::class],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_VALIDATION,
                Privileges::ENSEIGNEMENT_REALISE_VALIDATION,
                Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION,
                Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION,
            ],
            'resources'  => [Service::class,
                             VolumeHoraire::class,
                             Validation::class],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => Privileges::ENSEIGNEMENT_DEVALIDATION,
            'resources'  => Validation::class,
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::ENSEIGNEMENT_EXTERIEUR,
            ],
            'resources'  => [Intervenant::class,
                             Service::class],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
        [
            'privileges' => [
                Privileges::MOTIF_NON_PAIEMENT_VISUALISATION,
                Privileges::MOTIF_NON_PAIEMENT_EDITION,
                Privileges::TAG_EDITION,
                Privileges::TAG_VISUALISATION,
            ],
            'resources'  => Intervenant::class,
            'assertion'  => ServiceAssertion::class,
        ],
    ],

    'guards' => [
        [
            'controller' => EnseignementController::class,
            'action'     => ['saisie',
                             'rafraichir-ligne',
                             'saisie-form-refresh-vh',
                             'suppression',
                             'initialisation',
                             'constatation'],
            'privileges' => [
                Privileges::ENSEIGNEMENT_PREVU_EDITION,
                Privileges::ENSEIGNEMENT_REALISE_EDITION,
                Privileges::REFERENTIEL_PREVU_EDITION,
                Privileges::REFERENTIEL_REALISE_EDITION,
            ],
            'assertion'  => Assertion\EnseignementAssertion::class,
        ],
    ],


    'controllers' => [
        EnseignementController::class  => InvokableFactory::class,
        VolumeHoraireController::class => InvokableFactory::class,
    ],


    'services' => [
        Processus\EnseignementProcessus::class           => InvokableFactory::class,
        Processus\ValidationEnseignementProcessus::class => InvokableFactory::class,
        ServiceService::class                            => InvokableFactory::class,
        VolumeHoraireService::class                      => InvokableFactory::class,
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