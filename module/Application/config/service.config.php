<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;

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
                                'action' => 'intervenant',
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
                        'label'    => 'Enseignements',
                        'title'    => "Résumé des enseignements",
                        'route'    => 'service/resume',
                        'resource' => 'controller/Application\Controller\Service:index',
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
                    'action' => array('intervenant', 'saisie', 'suppression', 'voir', 'rafraichir-ligne', 'volumes-horaires-refresh'),
                    'roles' => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID,'Administrateur')),
                array(
                    'controller' => 'Application\Controller\Service',
                    'action' => array('index', 'resume','resume-refresh','filtres'),
                    'roles' => array(ComposanteRole::ROLE_ID,'Administrateur')),
                array( 
                    'controller' => 'Application\Controller\ServiceReferentiel',
                    'action' => array('index', 'intervenant', 'saisir', 'supprimer', 'voir', 'voirLigne', 'voirListe'),
                    'roles' => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID,'Administrateur')),
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
            'ApplicationModificationServiceDu'  => 'Application\\Service\\ModificationServiceDu',
            'ApplicationTypeVolumeHoraire'      => 'Application\\Service\\TypeVolumeHoraire',
            'FormServiceRechercheHydrator'      => 'Application\Form\Service\RechercheHydrator',
            'FormServiceSaisieFieldsetHydrator' => 'Application\Form\Service\SaisieFieldsetHydrator',
            'FormServiceSaisieHydrator'         => 'Application\Form\Service\SaisieHydrator',
            'ProcessFormuleHetd'                => 'Application\\Service\\Process\\FormuleHetd',
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
            'serviceSaisieForm'       => 'Application\View\Helper\Service\SaisieForm',
            'serviceResume'           => 'Application\View\Helper\Service\Resume',
        ),
        'factories' => array(
            'serviceListe'            => 'Application\View\Helper\Service\ListeFactory',
            'serviceLigne'            => 'Application\View\Helper\Service\LigneFactory',
            'serviceReferentielListe' => 'Application\View\Helper\ServiceReferentiel\ListeFactory',
            'serviceReferentielLigne' => 'Application\View\Helper\ServiceReferentiel\LigneFactory',
        ),
    ),
);
