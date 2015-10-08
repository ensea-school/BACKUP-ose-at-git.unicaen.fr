<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'service' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/service',
                    'defaults' => [
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Service',
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'resume' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/resume',
                            'defaults' => [
                                'action' => 'resume',
                            ],
                        ],
                    ],
                    'export' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/export',
                            'defaults' => [
                                'action' => 'export',
                            ],
                        ],
                    ],
                    'resume-refresh' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/resume-refresh',
                            'defaults' => [
                                'action' => 'resumeRefresh',
                            ],
                        ],
                    ],
                    'horodatage' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/horodatage/:intervenant/:typeVolumeHoraire/:referentiel',
                            'constraints' => [
                                'intervenant'       => '[0-9]*',
                                'typeVolumeHoraire' => '[0-9]*',
                                'referentiel'       => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'horodatage',
                            ],
                        ],
                    ],
                    'modifier' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/modifier/:id',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'modifier',
                            ],
                        ],
                    ],
                    'recherche' => [
                        'type'    => 'Segment',
                        'options' => [
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
                            'route'    => '/rafraichir-ligne/:service',
                            'constraints' => [
                                'service'=> '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'rafraichir-ligne',
                            ],
                        ],
                    ],
                    'intervenant' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/intervenant/:intervenant',
                            'constraints' => [
                                'intervenant'     => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/saisie[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'constatation' => [
                        'type'  => 'Segment',
                        'options' => [
                            'route'    => '/constatation',
                            'defaults' => [
                                'action' => 'constatation',
                            ],
                        ],
                    ],
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                                'id'     => '[0-9]*',
                            ],
                        ],
                    ],
                    'initialisation' => [
                        'type'  => 'Segment',
                        'options' => [
                            'route'    => '/initialisation/:intervenant',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'action' => 'initialisation',
                            ],
                        ],
                    ],
                ],
            ],
            'referentiel' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/referentiel',
                    'defaults' => [
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'   => 'ServiceReferentiel',
                    ],
                ],
                'may_terminate' => FALSE,
                'child_routes' => [
                    'saisie' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/saisie[/:id]',
                            'constraints' => [
                                'id' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'saisie',
                            ],
                        ],
                    ],
                    'rafraichir-ligne' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/rafraichir-ligne/:serviceReferentiel',
                            'constraints' => [
                                'serviceReferentiel'=> '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'rafraichir-ligne',
                            ],
                        ],
                    ],
                    'constatation' => [
                        'type'  => 'Segment',
                        'options' => [
                            'route'    => '/constatation',
                            'defaults' => [
                                'action' => 'constatation',
                            ],
                        ],
                    ],
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/:action[/:id]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'initialisation' => [
                        'type'  => 'Segment',
                        'options' => [
                            'route'    => '/initialisation/:intervenant',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                                'action' => 'initialisation',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'service' => [
                        'label'    => 'Enseignements',
                        'title'    => "Résumé des enseignements",
                        'route'    => 'service/resume',
                        'resource' => 'controller/Application\Controller\Service:resume',
                        'pages' => [
//                            'consultation' => array(
//                                'label'  => "Consultation",
//                                'title'  => "Consultation des services",
//                                'route'  => 'service',
//                                'visible' => true,
//                                'withtarget' => true,
//                                'pages' => array(),
//                            ),
                        ],
                    ],
                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Service',
                    'action' => ['index', 'export', 'saisie', 'suppression', 'rafraichir-ligne', 'volumes-horaires-refresh', 'initialisation', 'constatation', 'cloturer-saisie','horodatage'],
                    'roles' => [R_ROLE],
                ], [
                    'controller' => 'Application\Controller\Service',
                    'action' => ['resume','resume-refresh','recherche'],
                    'roles' => [R_ADMINISTRATEUR, R_COMPOSANTE, R_DRH, R_ETABLISSEMENT]
                ], [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action' => ['index', 'saisie', 'suppression', 'rafraichir-ligne', 'initialisation', 'constatation'],
                    'roles' => [R_ROLE],
                ],
            ],
        ],
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Service' => [],
                'ServiceReferentiel' => [],
                'ServiceListView' => [],
                'ServiceController' => [],
            ],
        ],
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                 'allow' => [
                    [
                        [R_ROLE],
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
                        [R_INTERVENANT, /*R_INTERVENANT_PERMANENT,*/ R_COMPOSANTE, R_ADMINISTRATEUR],
                        'ServiceReferentiel',
                        ['create', 'read', 'delete', 'update'],
                        'ServiceReferentielAssertion'
                    ]
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Service'            => Application\Controller\ServiceController::class,
            'Application\Controller\ServiceReferentiel' => Application\Controller\ServiceReferentielController::class,
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationService'                           => Application\Service\Service::class,
            'ApplicationServiceReferentiel'                => Application\Service\ServiceReferentiel::class,
            'ApplicationFonctionReferentiel'               => Application\Service\FonctionReferentiel::class,
            'ApplicationPeriode'                           => Application\Service\Periode::class,
            'ApplicationMotifNonPaiement'                  => Application\Service\MotifNonPaiement::class,
            'ApplicationModificationServiceDu'             => Application\Service\ModificationServiceDu::class,
            'ServiceRechercheHydrator'                     => Application\Entity\Service\RechercheHydrator::class,
            'ServiceRechercheFormHydrator'                 => Application\Form\Service\RechercheFormHydrator::class,
            'FormServiceSaisieFieldsetHydrator'            => Application\Form\Service\SaisieFieldsetHydrator::class,
            'FormServiceSaisieHydrator'                    => Application\Form\Service\SaisieHydrator::class,
            'FormServiceReferentielSaisieFieldsetHydrator' => Application\Form\ServiceReferentiel\SaisieFieldsetHydrator::class,
            'FormServiceReferentielSaisieHydrator'         => Application\Form\ServiceReferentiel\SaisieHydrator::class,
            'ServiceAssertion'                             => Application\Assertion\ServiceAssertion::class,
            'ServiceReferentielAssertion'                  => Application\Assertion\ServiceReferentielAssertion::class,
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'ServiceSaisie'                    => Application\Form\Service\Saisie::class,
            'ServiceSaisieFieldset'            => Application\Form\Service\SaisieFieldset::class,
            'ServiceReferentielSaisie'         => Application\Form\ServiceReferentiel\Saisie::class,
            'ServiceReferentielSaisieFieldset' => Application\Form\ServiceReferentiel\SaisieFieldset::class,
            'ServiceRechercheForm'             => Application\Form\Service\RechercheForm::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'serviceSaisieForm'            => Application\View\Helper\Service\SaisieForm::class,
            'formServiceReferentielSaisie' => Application\View\Helper\ServiceReferentiel\FormSaisie::class,
            'serviceResume'                => Application\View\Helper\Service\Resume::class,
            'FonctionReferentiel'          => Application\View\Helper\ServiceReferentiel\FonctionReferentielViewHelper::class,
        ],
        'factories' => [
            'serviceListe'            => Application\View\Helper\Service\ListeFactory::class,
            'serviceLigne'            => Application\View\Helper\Service\LigneFactory::class,
            'serviceReferentielListe' => Application\View\Helper\ServiceReferentiel\ListeFactory::class,
            'serviceReferentielLigne' => Application\View\Helper\ServiceReferentiel\LigneFactory::class,
        ],
        'javascript' => [
            '/test.js'
        ],
        'css' => [

        ],
    ],
];
