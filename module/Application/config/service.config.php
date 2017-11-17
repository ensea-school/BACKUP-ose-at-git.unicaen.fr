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
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Service',
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
                                'intervenant'       => '[0-9]*',
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
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'ServiceReferentiel',
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
                        'label'    => 'Enseignements',
                        'title'    => "Résumé des enseignements",
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
                    'assertion'  => 'assertionService',
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['saisie', 'suppression', 'rafraichir-ligne', 'volumes-horaires-refresh', 'initialisation', 'constatation', 'horodatage'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_EDITION,
                        Privileges::REFERENTIEL_EDITION,
                    ],
                    'assertion'  => 'assertionService',
                ],
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['validation'],
                    'privileges' => [
                        Privileges::ENSEIGNEMENT_VISUALISATION,
                    ],
                    'assertion'  => 'assertionService',
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
                    'assertion'  => 'assertionService',
                ],
                [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['saisie', 'suppression', 'rafraichir-ligne', 'initialisation', 'constatation'],
                    'privileges' => [
                        Privileges::REFERENTIEL_EDITION,
                    ],
                    'assertion'  => 'assertionService',
                ],
                [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['validation'],
                    'privileges' => [
                        Privileges::REFERENTIEL_VISUALISATION,
                    ],
                    'assertion'  => 'assertionService',
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
                    'assertion'  => 'assertionService',
                ],
            ],
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['cloturer-saisie'],
                    'roles'      => ['user'],
                    'assertion'  => 'assertionService',
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
                        'assertion'  => 'assertionService',
                    ],
                    [
                        'privileges' => Privileges::ENSEIGNEMENT_VALIDATION,
                        'resources'  => 'Validation',
                        'assertion'  => 'assertionService',
                    ],
                    [
                        'privileges' => Privileges::ENSEIGNEMENT_DEVALIDATION,
                        'resources'  => 'Validation',
                        'assertion'  => 'assertionService',
                    ],
                    [
                        'privileges' => [
                            Privileges::ENSEIGNEMENT_EXTERIEUR,
                        ],
                        'resources'  => ['Intervenant', 'Service'],
                        'assertion'  => 'assertionService',
                    ],
                    [
                        'privileges' => [
                            Privileges::MOTIF_NON_PAIEMENT_VISUALISATION,
                            Privileges::MOTIF_NON_PAIEMENT_EDITION,
                        ],
                        'resources'  => 'Intervenant',
                        'assertion'  => 'assertionService',
                    ],

                    /* Référentiel */
                    [
                        'privileges' => [
                            Privileges::REFERENTIEL_VISUALISATION,
                            Privileges::REFERENTIEL_EDITION,
                        ],
                        'resources'  => ['ServiceReferentiel', 'Intervenant'],
                        'assertion'  => 'assertionService',
                    ],
                    [
                        'privileges' => Privileges::REFERENTIEL_VALIDATION,
                        'resources'  => 'Validation',
                        'assertion'  => 'assertionService',
                    ],
                    [
                        'privileges' => Privileges::REFERENTIEL_DEVALIDATION,
                        'resources'  => 'Validation',
                        'assertion'  => 'assertionService',
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
            'ApplicationService'                 => Service\ServiceService::class,
            'ApplicationServiceReferentiel'      => Service\ServiceReferentiel::class,
            'ApplicationFonctionReferentiel'     => Service\FonctionReferentiel::class,
            'ApplicationPeriode'                 => Service\Periode::class,
            'ApplicationMotifNonPaiement'        => Service\MotifNonPaiement::class,
            'ApplicationModificationServiceDu'   => Service\ModificationServiceDu::class,
            Service\CampagneSaisieService::class => Service\CampagneSaisieService::class,
            'assertionService'                   => Assertion\ServiceAssertion::class,
            'processusService'                   => Processus\ServiceProcessus::class,
            'processusServiceReferentiel'        => Processus\ServiceReferentielProcessus::class,
        ],
    ],
    'hydrators'       => [
        'invokables' => [
            'serviceRecherche' => Hydrator\Service\RechercheHydrator::class,
        ],
    ],
    'form_elements'   => [
        'invokables' => [
            'ServiceSaisie'                    => Form\Service\Saisie::class,
            'ServiceSaisieFieldset'            => Form\Service\SaisieFieldset::class,
            'ServiceReferentielSaisie'         => Form\ServiceReferentiel\Saisie::class,
            'ServiceReferentielSaisieFieldset' => Form\ServiceReferentiel\SaisieFieldset::class,
            'ServiceRechercheForm'             => Form\Service\RechercheForm::class,
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
