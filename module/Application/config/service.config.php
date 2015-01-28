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
                ],
            ],
//            'service-ref' => [
//                'type' => 'Literal',
//                'options' => [
//                    'route' => '/service-referentiel',
//                    'defaults' => [
//                       '__NAMESPACE__' => 'Application\Controller',
//                        'controller'   => 'ServiceReferentiel',
//                    ],
//                ],
//                'may_terminate' => FALSE,
//                'child_routes' => [
//                    'modifier' => [
//                        'type'    => 'Segment',
//                        'options' => [
//                            'route'    => '/modifier/:id',
//                            'constraints' => [
//                                'id' => '[0-9]*',
//                            ],
//                            'defaults' => [
//                                'action' => 'modifier',
//                            ],
//                        ],
//                    ],
//                    'recherche' => [
//                        'type'    => 'Segment',
//                        'options' => [
//                            'route'    => '/recherche[/:term]',
//                            'defaults' => [
//                                'action' => 'recherche',
//                            ],
//                        ],
//                    ],
//                    'default' => [
//                        'type'    => 'Segment',
//                        'options' => [
//                            'route'    => '/:action[/:id]',
//                            'constraints' => [
//                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                'id'     => '[0-9]*',
//                            ],
//                            'defaults' => [
//                                'action' => 'index',
//                            ],
//                        ],
//                    ],
//                ],
//            ],
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
                    'action' => ['index', 'export', 'saisie', 'suppression', 'voir', 'rafraichir-ligne', 'volumes-horaires-refresh'],
                    'roles' => [R_ROLE],
                ], [
                    'controller' => 'Application\Controller\Service',
                    'action' => ['resume','resume-refresh','recherche'],
                    'roles' => [R_ADMINISTRATEUR, R_COMPOSANTE, R_RESPONSABLE_RECHERCHE_LABO, R_DRH, R_ETABLISSEMENT, R_FOAD]
                ], [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action' => ['index', 'intervenant', 'saisir', 'supprimer', 'voir', 'voirLigne', 'voirListe'],
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
                        [R_INTERVENANT_PERMANENT, R_COMPOSANTE, R_ADMINISTRATEUR],
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
            'Application\Controller\Service'            => 'Application\Controller\ServiceController',
            'Application\Controller\ServiceReferentiel' => 'Application\Controller\ServiceReferentielController',
        ],
        'initializers' => [
            'Application\Service\Initializer\ServiceServiceAwareInitializer',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'ApplicationService'                => 'Application\\Service\\Service',
            'ApplicationServiceReferentiel'     => 'Application\\Service\\ServiceReferentiel',
            'ApplicationFonctionReferentiel'     => 'Application\\Service\\FonctionReferentiel',
            'ApplicationServiceValidation'      => 'Application\\Service\\ServiceValidation',
            'ApplicationPeriode'                => 'Application\\Service\\Periode',
            'ApplicationMotifNonPaiement'       => 'Application\\Service\\MotifNonPaiement',
            'ApplicationModificationServiceDu'  => 'Application\\Service\\ModificationServiceDu',
            'ServiceRechercheHydrator'          => 'Application\Entity\Service\RechercheHydrator',
            'ServiceRechercheFormHydrator'      => 'Application\Form\Service\RechercheFormHydrator',
            'FormServiceSaisieFieldsetHydrator' => 'Application\Form\Service\SaisieFieldsetHydrator',
            'FormServiceSaisieHydrator'         => 'Application\Form\Service\SaisieHydrator',
            'ServiceAssertion'                  => 'Application\\Assertion\\ServiceAssertion',
            'ServiceReferentielAssertion'       => 'Application\\Assertion\\ServiceReferentielAssertion',
        ],
        'factories' => [
        ],
        'initializers' => [
            'Application\Service\Initializer\ServiceServiceAwareInitializer',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'ServiceSaisie'         => 'Application\\Form\\Service\\Saisie',
            'ServiceSaisieFieldset' => 'Application\\Form\\Service\\SaisieFieldset',
            'ServiceRechercheForm'  => 'Application\\Form\\Service\\RechercheForm',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'serviceDl'               => 'Application\View\Helper\Service\Dl',
            'serviceReferentielDl'    => 'Application\View\Helper\ServiceReferentiel\Dl',
            'serviceSaisieForm'       => 'Application\View\Helper\Service\SaisieForm',
            'serviceResume'           => 'Application\View\Helper\Service\Resume',
        ],
        'factories' => [
            'serviceListe'            => 'Application\View\Helper\Service\ListeFactory',
            'serviceLigne'            => 'Application\View\Helper\Service\LigneFactory',
            'serviceReferentielListe' => 'Application\View\Helper\ServiceReferentiel\ListeFactory',
            'serviceReferentielLigne' => 'Application\View\Helper\ServiceReferentiel\LigneFactory',
        ],
        'javascript' => [
            '/test.js'
        ],
        'css' => [

        ],
    ],
];
