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
                    'voirLigne' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/voirLigne[/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voirLigne',
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
                    'intervenant' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/intervenant/:intervenant',
                            'constraints' => array(
                                'intervenant'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'intervenant',
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
                        'controller'    => 'ServiceReferentiel',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
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
                        'label'    => 'Services',
                        'title'    => "Résumé des services",
                        'route'    => 'service/resume',
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
                    'roles' => array('user')),
                array(
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Service'            => 'Application\Controller\ServiceController',
            'Application\Controller\ServiceReferentiel' => 'Application\Controller\ServiceReferentielController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationService'                => 'Application\\Service\\Service',
            'ApplicationServiceReferentiel'     => 'Application\\Service\\ServiceReferentiel',
            'ApplicationServiceValidation'      => 'Application\\Service\\ServiceValidation',
            'ApplicationPeriode'                => 'Application\\Service\\Periode',
            'ApplicationMotifNonPaiement'       => 'Application\\Service\\MotifNonPaiement',
            'FormServiceRechercheHydrator'      => 'Application\Form\Service\RechercheHydrator',
            'FormServiceSaisieFieldsetHydrator' => 'Application\Form\Service\SaisieFieldsetHydrator',
        ),
        'factories' => array(
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
        ),
        'factories' => array(
            'serviceListe'            => 'Application\View\Helper\Service\ListeFactory',
            'serviceLigne'            => 'Application\View\Helper\Service\LigneFactory',
            'serviceReferentielListe' => 'Application\View\Helper\ServiceReferentiel\ListeFactory',
            'serviceReferentielLigne' => 'Application\View\Helper\ServiceReferentiel\LigneFactory',
        ),
    ),
);
