<?php

namespace Application;

use UnicaenAuth\Guard\PrivilegeController;

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
                    'resume'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/resume',
                            'defaults' => [
                                'action' => 'resume',
                            ],
                        ],
                    ],
                    'export'           => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/export',
                            'defaults' => [
                                'action' => 'export',
                            ],
                        ],
                    ],
                    'resume-refresh'   => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/resume-refresh',
                            'defaults' => [
                                'action' => 'resumeRefresh',
                            ],
                        ],
                    ],
                    'horodatage'       => [
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
                    'modifier'         => [
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
                    'recherche'        => [
                        'type'         => 'Segment',
                        'options'      => [
                            'route'    => '/recherche',
                            'defaults' => [
                                'action' => 'recherche',
                            ],
                        ],
                        'child_routes' => [
                            'default' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/',
                                    'defaults' => [
                                        'action' => 'recherche',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'rafraichir-ligne' => [
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
                    'intervenant'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/intervenant/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'saisie'           => [
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
                    'constatation'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/constatation',
                            'defaults' => [
                                'action' => 'constatation',
                            ],
                        ],
                    ],
                    'default'          => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults'    => [
                                'action' => 'index',
                                'id'     => '[0-9]*',
                            ],
                        ],
                    ],
                    'initialisation'   => [
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
                    'saisie'           => [
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
                    'rafraichir-ligne' => [
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
                    'constatation'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/constatation',
                            'defaults' => [
                                'action' => 'constatation',
                            ],
                        ],
                    ],
                    'default'          => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults'    => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'initialisation'   => [
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
                        'pages'    => [
//                            'consultation' => array(
//                                'label'  => "Consultation",
//                                'title'  => "Consultation des services",
//                                'route'  => 'service',
//                                'visible' => true,
//                                'pages' => array(),
//                            ),
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize'    => [
        'guards'             => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['index', 'export', 'saisie', 'suppression', 'rafraichir-ligne', 'volumes-horaires-refresh', 'initialisation', 'constatation', 'cloturer-saisie', 'horodatage'],
                    'roles'      => ['user'],
                ], [
                    'controller' => 'Application\Controller\Service',
                    'action'     => ['resume', 'resume-refresh', 'recherche'],
                    'roles'      => [R_ADMINISTRATEUR, R_COMPOSANTE, R_ETABLISSEMENT],
                ], [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action'     => ['index', 'saisie', 'suppression', 'rafraichir-ligne', 'initialisation', 'constatation'],
                    'roles'      => ['user'],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Service'            => [],
                'ServiceReferentiel' => [],
                'ServiceListView'    => [],
                'ServiceController'  => [],
            ],
        ],
        'rule_providers'     => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                    [
                        ['user'],
                        'Service',
                        ['create', 'read', 'delete', 'update'],
                        'ServiceAssertion',
                    ],
                    [
                        [R_COMPOSANTE],
                        'ServiceListView',
                        ['info-only-structure'],
                        'ServiceAssertion',
                    ],
                    [
                        [R_INTERVENANT],
                        'ServiceListView',
                        ['aide-intervenant'],
                        'ServiceAssertion',
                    ],
                    [
                        [R_INTERVENANT, R_COMPOSANTE, R_ADMINISTRATEUR],
                        'ServiceReferentiel',
                        ['create', 'read', 'delete', 'update'],
                        'ServiceReferentielAssertion',
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
            'ApplicationService'                           => Service\ServiceService::class,
            'ApplicationServiceReferentiel'                => Service\ServiceReferentiel::class,
            'ApplicationFonctionReferentiel'               => Service\FonctionReferentiel::class,
            'ApplicationPeriode'                           => Service\Periode::class,
            'ApplicationMotifNonPaiement'                  => Service\MotifNonPaiement::class,
            'ApplicationModificationServiceDu'             => Service\ModificationServiceDu::class,
            'ServiceRechercheFormHydrator'                 => Form\Service\RechercheFormHydrator::class,
            'FormServiceReferentielSaisieFieldsetHydrator' => Form\ServiceReferentiel\SaisieFieldsetHydrator::class,
            'FormServiceReferentielSaisieHydrator'         => Form\ServiceReferentiel\SaisieHydrator::class,
            'ServiceAssertion'                             => Assertion\ServiceAssertion::class,
            'ServiceReferentielAssertion'                  => Assertion\ServiceReferentielAssertion::class,
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
