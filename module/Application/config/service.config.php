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
                    'export-csv'               => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/export-csv',
                            'defaults' => [
                                'action' => 'export-csv',
                            ],
                        ],
                    ],
                    'export-pdf'               => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/export-pdf',
                            'defaults' => [
                                'action' => 'export-pdf',
                            ],
                        ],
                    ],
                    'modifier'                 => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'suppression'              => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/suppression/:service',
                            'constraints' => [
                                'service' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'suppression',
                            ],
                        ],
                    ],
                    'recherche'                => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/recherche',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                    ],
                    'rafraichir-ligne'         => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/rafraichir-ligne/:service',
                            'constraints' => [
                                'service' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'rafraichir-ligne',
                            ],
                        ],
                    ],
                    'saisie'                   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/saisie[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'constatation'             => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/constatation',
                            'defaults' => [
                                'action' => 'constatation',
                            ],
                        ],
                    ],
                    'volumes-horaires-refresh' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/volumes-horaires-refresh[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'volumes-horaires-refresh',
                            ],
                        ],
                    ],
                    'initialisation'           => [
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
                    'action'     => ['saisie', 'suppression', 'rafraichir-ligne', 'volumes-horaires-refresh', 'initialisation', 'constatation'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_PREVU_EDITION,
                        Privileges::ENSEIGNEMENT_REALISE_EDITION,
                        Privileges::REFERENTIEL_PREVU_EDITION,
                        Privileges::REFERENTIEL_REALISE_EDITION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                /*[
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['import-agenda'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_IMPORT_INTERVENANT_PREVISIONNEL_AGENDA,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],*/
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
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['export-csv'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_EXPORT_CSV,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['export-pdf'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_EXPORT_PDF,
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
            'Application\Controller\Service' => Controller\ServiceController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\ServiceService::class          => Service\ServiceService::class,
            Service\PeriodeService::class          => Service\PeriodeService::class,
            Service\MotifNonPaiementService::class => Service\MotifNonPaiementService::class,
            Processus\ServiceProcessus::class      => Processus\ServiceProcessus::class,
        ],
        'factories'  => [
            Assertion\ServiceAssertion::class => \UnicaenAuth\Assertion\AssertionFactory::class,
        ],
    ],
    'form_elements'   => [
        'factories'  => [
            Form\Service\SaisieFieldset::class => Form\Service\Factory\SaisieFieldsetFactory::class,
            Form\Service\RechercheForm::class  => Form\Service\Factory\RechercheFormFactory::class,
        ],
        'invokables' => [
            Form\Service\Saisie::class => Form\Service\Saisie::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'serviceSaisieForm'            => View\Helper\Service\SaisieForm::class,
            'formServiceReferentielSaisie' => View\Helper\ServiceReferentiel\FormSaisie::class,
            'serviceResume'                => View\Helper\Service\Resume::class,
            'fonctionReferentiel'          => View\Helper\ServiceReferentiel\FonctionReferentielViewHelper::class,
        ],
        'factories'  => [
            'serviceListe'            => View\Helper\Service\ListeFactory::class,
            'serviceLigne'            => View\Helper\Service\LigneFactory::class,
            'serviceReferentielListe' => View\Helper\ServiceReferentiel\ListeFactory::class,
            'serviceReferentielLigne' => View\Helper\ServiceReferentiel\LigneFactory::class,
        ],
    ],
];
