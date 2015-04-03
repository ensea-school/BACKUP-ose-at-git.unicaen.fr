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
                    'action' => ['index', 'export', 'saisie', 'suppression', 'voir', 'rafraichir-ligne', 'volumes-horaires-refresh','constatation'],
                    'roles' => [R_ROLE],
                ], [
                    'controller' => 'Application\Controller\Service',
                    'action' => ['resume','resume-refresh','recherche'],
                    'roles' => [R_ADMINISTRATEUR, R_COMPOSANTE, R_RESPONSABLE_RECHERCHE_LABO, R_DRH, R_ETABLISSEMENT, R_FOAD]
                ], [
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action' => ['index', 'saisie', 'suppression', 'rafraichir-ligne', 'constatation'],
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
            'ApplicationService'                           => 'Application\\Service\\Service',
            'ApplicationServiceReferentiel'                => 'Application\\Service\\ServiceReferentiel',
            'ApplicationFonctionReferentiel'               => 'Application\\Service\\FonctionReferentiel',
            'ApplicationPeriode'                           => 'Application\\Service\\Periode',
            'ApplicationMotifNonPaiement'                  => 'Application\\Service\\MotifNonPaiement',
            'ApplicationModificationServiceDu'             => 'Application\\Service\\ModificationServiceDu',
            'ServiceRechercheHydrator'                     => 'Application\Entity\Service\RechercheHydrator',
            'ServiceRechercheFormHydrator'                 => 'Application\Form\Service\RechercheFormHydrator',
            'FormServiceSaisieFieldsetHydrator'            => 'Application\Form\Service\SaisieFieldsetHydrator',
            'FormServiceSaisieHydrator'                    => 'Application\Form\Service\SaisieHydrator',
            'FormServiceReferentielSaisieFieldsetHydrator' => 'Application\Form\ServiceReferentiel\SaisieFieldsetHydrator',
            'FormServiceReferentielSaisieHydrator'         => 'Application\Form\ServiceReferentiel\SaisieHydrator',
            'ServiceAssertion'                             => 'Application\\Assertion\\ServiceAssertion',
            'ServiceReferentielAssertion'                  => 'Application\\Assertion\\ServiceReferentielAssertion',
        ],
        'factories' => [
        ],
        'initializers' => [
            'Application\Service\Initializer\ServiceServiceAwareInitializer',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'ServiceSaisie'                    => 'Application\\Form\\Service\\Saisie',
            'ServiceSaisieFieldset'            => 'Application\\Form\\Service\\SaisieFieldset',
            'ServiceReferentielSaisie'         => 'Application\\Form\\ServiceReferentiel\\Saisie',
            'ServiceReferentielSaisieFieldset' => 'Application\\Form\\ServiceReferentiel\\SaisieFieldset',
            'ServiceRechercheForm'             => 'Application\\Form\\Service\\RechercheForm',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'serviceDl'                    => 'Application\View\Helper\Service\Dl',
            'serviceReferentielDl'         => 'Application\View\Helper\ServiceReferentiel\Dl',
            'serviceSaisieForm'            => 'Application\View\Helper\Service\SaisieForm',
            'formServiceReferentielSaisie' => 'Application\View\Helper\ServiceReferentiel\FormSaisie',
            'serviceResume'                => 'Application\View\Helper\Service\Resume',
            'FonctionReferentiel'     => 'Application\View\Helper\ServiceReferentiel\FonctionReferentielViewHelper',
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
