<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'service' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/service',
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Service',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'resume' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/resume',
                            'defaults' => array(
                                'action' => 'resume',
                            ),
                        ),
                    ),
                    'export' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/export',
                            'defaults' => array(
                                'action' => 'export',
                            ),
                        ),
                    ),
                    'resume-refresh' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/resume-refresh',
                            'defaults' => array(
                                'action' => 'resumeRefresh',
                            ),
                        ),
                    ),
                    'modifier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/modifier/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'modifier',
                            ),
                        ),
                    ),
                    'recherche' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/recherche[/:term]',
                            'defaults' => array(
                                'action' => 'recherche',
                            ),
                        ),
                    ),
                    'rafraichir-ligne' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/rafraichir-ligne/:service/:typeVolumeHoraire',
                            'constraints' => array(
                                'service'=> '[0-9]*',
                                'typeVolumeHoraire' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'rafraichir-ligne',
                            ),
                        ),
                    ),
                    'intervenant' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/intervenant/:intervenant',
                            'constraints' => array(
                                'intervenant'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                                'id'     => '[0-9]*',
                            ),
                        ),
                    ),
                ),
            ),
            'service-ref' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/service-referentiel',
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'   => 'ServiceReferentiel',
                    ),
                ),
                'may_terminate' => FALSE,
                'child_routes' => array(
                    'modifier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/modifier/:id',
                            'constraints' => array(
                                'id' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'modifier',
                            ),
                        ),
                    ),
                    'recherche' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/recherche[/:term]',
                            'defaults' => array(
                                'action' => 'recherche',
                            ),
                        ),
                    ),
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'service' => array(
                        'label'    => 'Enseignements',
                        'title'    => "Résumé des enseignements",
                        'route'    => 'service/resume',
                        'resource' => 'controller/Application\Controller\Service:resume',
                        'pages' => array(
//                            'consultation' => array(
//                                'label'  => "Consultation",
//                                'title'  => "Consultation des services",
//                                'route'  => 'service',
//                                'visible' => true,
//                                'withtarget' => true,
//                                'pages' => array(),
//                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Service',
                    'action' => array('index', 'export', 'saisie', 'suppression', 'voir', 'rafraichir-ligne', 'volumes-horaires-refresh'),
                    'roles' => [R_ROLE],
                ), array(
                    'controller' => 'Application\Controller\Service',
                    'action' => array('resume','resume-refresh','filtres'),
                    'roles' => [R_ADMINISTRATEUR, R_COMPOSANTE, R_RESPONSABLE_RECHERCHE_LABO, R_DRH, R_ETABLISSEMENT, R_FOAD]
                ), array(
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action' => array('index', 'intervenant', 'saisir', 'supprimer', 'voir', 'voirLigne', 'voirListe'),
                    'roles' => [R_ROLE],
                ),
            ),
        ),
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Service' => [],
                'ServiceReferentiel' => [],
                'ServiceListView' => [],
                'ServiceController' => [],
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(
                        [R_ROLE],
                        'Service',
                        array('create', 'read', 'delete', 'update'),
                        'ServiceAssertion',
                    ),
                    array(
                        [R_COMPOSANTE],
                        'ServiceListView',
                        array('info-only-structure'),
                        'ServiceAssertion',
                    ),
                    array(
                        [R_INTERVENANT],
                        'ServiceListView',
                        array('aide-intervenant'),
                        'ServiceAssertion',
                    ),
                    [
                        [R_INTERVENANT_PERMANENT, R_COMPOSANTE, R_ADMINISTRATEUR],
                        'ServiceReferentiel',
                        ['create', 'read', 'delete', 'update'],
                        'ServiceReferentielAssertion'
                    ]
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Service'            => 'Application\Controller\ServiceController',
            'Application\Controller\ServiceReferentiel' => 'Application\Controller\ServiceReferentielController',
        ),
        'initializers' => array(
            'Application\Service\Initializer\ServiceServiceAwareInitializer',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationService'                => 'Application\\Service\\Service',
            'ApplicationServiceReferentiel'     => 'Application\\Service\\ServiceReferentiel',
            'ApplicationFonctionReferentiel'     => 'Application\\Service\\FonctionReferentiel',
            'ApplicationServiceValidation'      => 'Application\\Service\\ServiceValidation',
            'ApplicationPeriode'                => 'Application\\Service\\Periode',
            'ApplicationMotifNonPaiement'       => 'Application\\Service\\MotifNonPaiement',
            'ApplicationModificationServiceDu'  => 'Application\\Service\\ModificationServiceDu',
            'FormServiceRechercheHydrator'      => 'Application\Form\Service\RechercheHydrator',
            'FormServiceSaisieFieldsetHydrator' => 'Application\Form\Service\SaisieFieldsetHydrator',
            'FormServiceSaisieHydrator'         => 'Application\Form\Service\SaisieHydrator',
            'ProcessFormuleHetd'                => 'Application\\Service\\Process\\FormuleHetd',
            'ServiceAssertion'                  => 'Application\\Assertion\\ServiceAssertion',
            'ServiceReferentielAssertion'       => 'Application\\Assertion\\ServiceReferentielAssertion',
        ),
        'factories' => array(
        ),
        'initializers' => array(
            'Application\Service\Initializer\ServiceServiceAwareInitializer',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'ServiceSaisie'         => 'Application\\Form\\Service\\Saisie',
            'ServiceSaisieFieldset' => 'Application\\Form\\Service\\SaisieFieldset',
        ),
        'factories' => array(
            'ServiceRecherche' => 'Application\\Form\\Service\\RechercheFactory',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'serviceDl'               => 'Application\View\Helper\Service\Dl',
            'serviceReferentielDl'    => 'Application\View\Helper\ServiceReferentiel\Dl',
            'serviceSaisieForm'       => 'Application\View\Helper\Service\SaisieForm',
            'serviceResume'           => 'Application\View\Helper\Service\Resume',
        ),
        'factories' => array(
            'serviceListe'            => 'Application\View\Helper\Service\ListeFactory',
            'serviceLigne'            => 'Application\View\Helper\Service\LigneFactory',
            'serviceReferentielListe' => 'Application\View\Helper\ServiceReferentiel\ListeFactory',
            'serviceReferentielLigne' => 'Application\View\Helper\ServiceReferentiel\LigneFactory',
        ),
        'javascript' => array(
            '/test.js'
        ),
        'css' => array(
            
        ),
    ),
);
