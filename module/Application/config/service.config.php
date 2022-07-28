<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'service' => [
                'child_routes' => [
                    'recherche'      => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/recherche',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],
                    'constatation'   => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/constatation',
                            'defaults' => [
                                'action' => 'constatation',
                            ],
                        ],
                    ],
                    'initialisation' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/initialisation/:intervenant',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults'    => [
                                'action' => 'initialisation',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'         => [
            PrivilegeController::class      => [
                /* Enseignements */
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['validation'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                        Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['valider'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_PREVU_VALIDATION,
                        Privileges::ENSEIGNEMENT_REALISE_VALIDATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['devalider'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_DEVALIDATION,
                    ],
                ],
            ],
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['cloturer-saisie'],
                    'roles'      => ['user'],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    /* Enseignements */
                    [
                        'privileges' => [
                            Privileges::ENSEIGNEMENT_PREVU_VISUALISATION,
                            Privileges::ENSEIGNEMENT_PREVU_EDITION,
                            Privileges::ENSEIGNEMENT_REALISE_VISUALISATION,
                            Privileges::ENSEIGNEMENT_REALISE_EDITION,
                        ],
                        'resources'  => ['Service', 'Intervenant'],
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => [
                            Privileges::ENSEIGNEMENT_PREVU_VALIDATION,
                            Privileges::ENSEIGNEMENT_REALISE_VALIDATION,
                            Privileges::ENSEIGNEMENT_PREVU_AUTOVALIDATION,
                            Privileges::ENSEIGNEMENT_REALISE_AUTOVALIDATION,
                        ],
                        'resources'  => ['Service', 'VolumeHoraire', 'Validation'],
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => Privileges::ENSEIGNEMENT_DEVALIDATION,
                        'resources'  => 'Validation',
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => [
                            Privileges::ENSEIGNEMENT_EXTERIEUR,
                        ],
                        'resources'  => ['Intervenant', 'Service'],
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => [
                            Privileges::MOTIF_NON_PAIEMENT_VISUALISATION,
                            Privileges::MOTIF_NON_PAIEMENT_EDITION,
                        ],
                        'resources'  => 'Intervenant',
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],

                    /* Référentiel */
                    [
                        'privileges' => [
                            Privileges::REFERENTIEL_PREVU_VISUALISATION,
                            Privileges::REFERENTIEL_PREVU_EDITION,
                            Privileges::REFERENTIEL_REALISE_VISUALISATION,
                            Privileges::REFERENTIEL_REALISE_EDITION,
                        ],
                        'resources'  => ['ServiceReferentiel', 'Intervenant'],
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => [
                            Privileges::REFERENTIEL_PREVU_VALIDATION,
                            Privileges::REFERENTIEL_REALISE_VALIDATION,
                            Privileges::REFERENTIEL_PREVU_AUTOVALIDATION,
                            Privileges::REFERENTIEL_REALISE_AUTOVALIDATION,
                        ],
                        'resources'  => ['ServiceReferentiel', 'VolumeHoraireReferentiel', 'Validation'],
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => Privileges::REFERENTIEL_DEVALIDATION,
                        'resources'  => 'Validation',
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            'Application\Controller\Service' => Controller\IntervenantController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\PeriodeService::class          => Service\PeriodeService::class,
            Service\MotifNonPaiementService::class => Service\MotifNonPaiementService::class,
        ],
        'factories'  => [
            Assertion\ServiceAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'formServiceReferentielSaisie' => View\Helper\ServiceReferentiel\FormSaisie::class,
            'serviceResume'                => View\Helper\Service\Resume::class,
            'fonctionReferentiel'          => View\Helper\ServiceReferentiel\FonctionReferentielViewHelper::class,
        ],
        'factories'  => [
            'serviceReferentielListe' => View\Helper\ServiceReferentiel\ListeFactory::class,
            'serviceReferentielLigne' => View\Helper\ServiceReferentiel\LigneFactory::class,
        ],
    ],
];
