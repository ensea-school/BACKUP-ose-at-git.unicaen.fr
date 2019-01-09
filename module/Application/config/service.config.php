<?php

namespace Application;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Guard\PrivilegeController;
use UnicaenAuth\Provider\Rule\PrivilegeRuleProvider;

return [
    'router'          => [
        'routes' => [
            'service'     => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/service',
                    'defaults' => [
                        'controller'    => 'Application\Controller\Service',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'resume'                   => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/resume',
                            'defaults' => [
                                'action' => 'resume',
                            ],
                        ],
                    ],
                    'export'                   => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/export',
                            'defaults' => [
                                'action' => 'export',
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
                    'resume-refresh'           => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/resume-refresh',
                            'defaults' => [
                                'action' => 'resumeRefresh',
                            ],
                        ],
                    ],
                    'horodatage'               => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/horodatage/:intervenant/:typeVolumeHoraire/:referentiel',
                            'constraints' => [
                                'typeVolumeHoraire' => '[0-9]*',
                                'referentiel'       => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'horodatage',
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
            'referentiel' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/referentiel',
                    'defaults' => [
                        'controller'    => 'Application\Controller\ServiceReferentiel',
                    ],
                ],
                'may_terminate' => false,
                'child_routes'  => [
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
                    'rafraichir-ligne'         => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/rafraichir-ligne/:serviceReferentiel',
                            'constraints' => [
                                'serviceReferentiel' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'rafraichir-ligne',
                            ],
                        ],
                    ],
                    'constatation'             => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/constatation',
                            'defaults' => [
                                'action' => 'constatation',
                            ],
                        ],
                    ],
                    'suppression'              => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/suppression/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'suppression',
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
    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'service' => [
                        'label'    => 'Services',
                        'title'    => "Visualisation et export des services",
                        'route'    => 'service/resume',
                        'resource' => PrivilegeController::getResourceId('Application\Controller\Service', 'resume'),
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            PrivilegeController::class      => [
                /* Enseignements */
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['saisie', 'suppression', 'rafraichir-ligne', 'volumes-horaires-refresh', 'initialisation', 'constatation', 'horodatage'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_EDITION,
                        Privileges::REFERENTIEL_EDITION,
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
                        Privileges::ENSEIGNEMENT_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['valider'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_VALIDATION,
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
                    'action'     => ['export'],
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

                /* Référentiel */
                [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['index'],
                    'privileges' => [
                        Privileges::REFERENTIEL_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['saisie', 'suppression', 'rafraichir-ligne', 'initialisation', 'constatation'],
                    'privileges' => [
                        Privileges::REFERENTIEL_EDITION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['validation'],
                    'privileges' => [
                        Privileges::REFERENTIEL_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
                ],
                [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['valider'],
                    'privileges' => [
                        Privileges::REFERENTIEL_VALIDATION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['devalider'],
                    'privileges' => [
                        Privileges::REFERENTIEL_DEVALIDATION,
                    ],
                ],

                /* Commun */
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['resume-refresh'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_EDITION,
                        Privileges::REFERENTIEL_EDITION,
                    ],
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['resume', 'recherche'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_VISUALISATION,
                        Privileges::REFERENTIEL_VISUALISATION,
                    ],
                    'assertion'  => Assertion\ServiceAssertion::class,
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
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'Service'            => [],
                'ServiceReferentiel' => [],
            ],
        ],
        'rule_providers'     => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    /* Enseignements */
                    [
                        'privileges' => [
                            Privileges::ENSEIGNEMENT_VISUALISATION,
                            Privileges::ENSEIGNEMENT_EDITION,
                        ],
                        'resources'  => ['Service', 'Intervenant'],
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => Privileges::ENSEIGNEMENT_VALIDATION,
                        'resources'  => 'Validation',
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
                            Privileges::REFERENTIEL_VISUALISATION,
                            Privileges::REFERENTIEL_EDITION,
                        ],
                        'resources'  => ['ServiceReferentiel', 'Intervenant'],
                        'assertion'  => Assertion\ServiceAssertion::class,
                    ],
                    [
                        'privileges' => Privileges::REFERENTIEL_VALIDATION,
                        'resources'  => 'Validation',
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
            'Application\Controller\Service'            => Controller\ServiceController::class,
            'Application\Controller\ServiceReferentiel' => Controller\ServiceReferentielController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            Service\ServiceService::class                => Service\ServiceService::class,
            Service\ServiceReferentielService::class     => Service\ServiceReferentielService::class,
            Service\FonctionReferentielService::class    => Service\FonctionReferentielService::class,
            Service\PeriodeService::class                => Service\PeriodeService::class,
            Service\MotifNonPaiementService::class       => Service\MotifNonPaiementService::class,
            Service\ModificationServiceDuService::class  => Service\ModificationServiceDuService::class,
            Service\CampagneSaisieService::class         => Service\CampagneSaisieService::class,
            Assertion\ServiceAssertion::class            => Assertion\ServiceAssertion::class,
            Processus\ServiceProcessus::class            => Processus\ServiceProcessus::class,
            Processus\ServiceReferentielProcessus::class => Processus\ServiceReferentielProcessus::class,
        ],
    ],
    'hydrators'       => [
        'invokables' => [
            Hydrator\Service\RechercheHydrator::class => Hydrator\Service\RechercheHydrator::class,
        ],
    ],
    'form_elements'   => [
        'factories' => [
            Form\Service\SaisieFieldset::class            => Form\Service\Factory\SaisieFieldsetFactory::class,
        ],
        'invokables' => [
            Form\Service\Saisie::class                    => Form\Service\Saisie::class,
            Form\ServiceReferentiel\Saisie::class         => Form\ServiceReferentiel\Saisie::class,
            Form\ServiceReferentiel\SaisieFieldset::class => Form\ServiceReferentiel\SaisieFieldset::class,
            Form\Service\RechercheForm::class             => Form\Service\RechercheForm::class,
        ],
    ],
    'view_helpers'    => [
        'invokables' => [
            'serviceSaisieForm'            => View\Helper\Service\SaisieForm::class,
            'formServiceReferentielSaisie' => View\Helper\ServiceReferentiel\FormSaisie::class,
            'serviceResume'                => View\Helper\Service\Resume::class,
            'FonctionReferentiel'          => View\Helper\ServiceReferentiel\FonctionReferentielViewHelper::class,
        ],
        'factories'  => [
            'serviceListe'            => View\Helper\Service\ListeFactory::class,
            'serviceLigne'            => View\Helper\Service\LigneFactory::class,
            'serviceReferentielListe' => View\Helper\ServiceReferentiel\ListeFactory::class,
            'serviceReferentielLigne' => View\Helper\ServiceReferentiel\LigneFactory::class,
        ],
    ],
];
