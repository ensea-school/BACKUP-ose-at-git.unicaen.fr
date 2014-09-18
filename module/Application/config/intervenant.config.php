<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;

define('ROUTE_DOSSIER',        'intervenant/saisir-dossier');
define('ROUTE_SERVICE',        'intervenant/services');
define('ROUTE_PIECES_JOINTES', 'intervenant/pieces-jointes');
    
return array(
    'router' => array(
        'routes' => array(
            'intervenant' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/intervenant',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Intervenant',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:action[/:intervenant]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'rechercher' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/rechercher[/:intervenant]',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'rechercher',
                            ),
                        ),
                    ),
                    'fiche' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir',
                                'intervenant' => 0,
                            ),
                        ),
                    ),
                    'voir-heures-comp' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/voir-heures-comp[/:intervenant]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir-heures-comp',
                            ),
                        ),
                    ),
                    'feuille-de-route' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/feuille-de-route',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'feuille-de-route',
                            ),
                        ),
                    ),
                    'modification-service-du' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/modification-service-du',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'ModificationServiceDu',
                                'action'     => 'saisir',
                            ),
                        ),
                    ),
                    'saisir-dossier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/saisir-dossier',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Dossier',
                                'action' => 'modifier',
                                'intervenant' => 0,
                            ),
                        ),
                    ),
                    'services' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/services',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Application\Controller\Service',
                                'action' => 'intervenant',
                                'intervenant' => 0,
                            ),
                        ),
                    ),
                    'pieces-jointes' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant/pieces-jointes',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Dossier',
                                'action' => 'pieces-jointes',
                                'intervenant' => 0,
                            ),
                        ),
                    ),
                    'validation-dossier' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/validation/dossier',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Validation',
                                'action'     => 'dossier',
                            ),
                        ),
                    ),
                    'validation-service' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/validation/service',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Validation',
                                'action' => 'service',
                            ),
                        ),
                    ),
                    'contrat' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/contrat',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Contrat',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'workflow' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route' => '/workflow/:intervenant/:action',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'intervenant' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Workflow',
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'intervenant' => array(
                        'label'    => 'Intervenant',
                        'title'    => "Intervenant",
                        'route'    => 'intervenant',
                        'resource' => 'controller/Application\Controller\Intervenant:index',
                        'pages' => array(
                            'rechercher' => array(
                                'label'  => " Rechercher",
                                'title'  => "Rechercher un intervenant",
                                'route'  => 'intervenant/rechercher',
                                'class'   => "iconify glyphicon glyphicon-search",
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:rechercher',
                            ),
                            'fiche' => array(
                                'label'  => "Fiche",
                                'title'  => "Consultation de la fiche de l'intervenant {id}",
                                'route'  => 'intervenant/fiche',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:voir',
                            ),
                            'voir-heures-comp' => array(
                                'label'  => "Heures complémentaires",
                                'title'  => "Calcul des heures complémentaires {id}",
                                'route'  => 'intervenant/voir-heures-comp',
                                'action' => 'voir-heures-comp',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Intervenant:voir-heures-comp',
                            ),
                            'modification-service-du' => array(
                                'label'  => "Modification de service dû",
                                'title'  => "Modification de service dû de l'intervenant {id}",
                                'route'  => 'intervenant/modification-service-du',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\ModificationServiceDu:saisir',
                            ),
//                            'feuille-de-route' => array(
//                                'label'  => "Feuille de route",
//                                'title'  => "Feuille de route de l'intervenant {id}",
//                                'route'  => 'intervenant/feuille-de-route',
//                                'withtarget' => true,
//                                'resource' => 'controller/Application\Controller\Intervenant:feuille-de-route',
//                            ),
                            'dossier' => array(
                                'label'  => "Données personnelles",
                                'title'  => "Saisir les données personnelles d'un intervenant vacataire",
                                'route'  => ROUTE_DOSSIER,
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Dossier:modifier',
                                'visible' => 'NavigationPageVisibility',
                            ),
                            'service' => array(
                                'label'  => "Enseignements",
                                'title'  => "Enseignements de l'intervenant",
                                'route'  => ROUTE_SERVICE,
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Service:intervenant',
                                'visible' => 'NavigationPageVisibility',
                            ),
                            'pieces-jointes' => array(
                                'label'  => "Pièces justificatives",
                                'title'  => "Pièces justificatives du dossier de l'intervenant",
                                'route'  => ROUTE_PIECES_JOINTES,
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Dossier:pieces-jointes',
                                'visible' => 'NavigationPageVisibility',
                            ),
                            'validation-dossier' => array(
                                'label'  => "Validation des données personnelles",
                                'title'  => "Validation des données personnelles de l'intervenant",
                                'route'  => 'intervenant/validation-dossier',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:dossier',
                                'visible' => 'NavigationPageVisibility',
                            ),
                            'validation-service' => array(
                                'label'  => "Validation des enseignements",
                                'title'  => "Validation des enseignements de l'intervenant",
                                'route'  => 'intervenant/validation-service',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:service',
                                'visible' => 'NavigationPageVisibility',
                            ),
                            'contrat' => array(
                                'label'  => "Contrat / avenant",
                                'title'  => "Contrat et avenants de l'intervenant",
                                'route'  => 'intervenant/contrat',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Contrat:index',
                                'visible' => 'NavigationPageVisibility',
                            ),
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
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('index', 'apercevoir', 'feuille-de-route'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID,'administrateur'),
                ),
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('voir', 'choisir', 'rechercher', 'search'),
                    'roles'      => array(ComposanteRole::ROLE_ID,'administrateur'),
                ),
                array(
                    'controller' => 'Application\Controller\Intervenant',
                    'action'     => array('voir-heures-comp'),
                    'roles'      => array('drh','administrateur'),
                ),
                array(
                    'controller' => 'Application\Controller\Dossier',
                    'action'     => array('voir', 'modifier', 'pieces-jointes'),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID,'administrateur'),
                ),
                array(
                    'controller' => 'Application\Controller\ModificationServiceDu',
                    'action'     => array('saisir'),
                    'roles'      => array(ComposanteRole::ROLE_ID,'administrateur'),
                ),
                array(
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => array('nav-next'),
                    'roles'      => array('user','administrateur'),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Intervenant'           => 'Application\Controller\IntervenantController',
            'Application\Controller\Dossier'               => 'Application\Controller\DossierController',
            'Application\Controller\ModificationServiceDu' => 'Application\Controller\ModificationServiceDuController',
            'Application\Controller\Workflow'              => 'Application\Controller\WorkflowController',
        ),
        'aliases' => array(
            'IntervenantController' => 'Application\Controller\Intervenant',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationOffreFormation'        => 'Application\\Service\\OffreFormation',
            'ApplicationIntervenant'           => 'Application\\Service\\Intervenant',
            'ApplicationCivilite'              => 'Application\\Service\\Civilite',
            'ApplicationStatutIntervenant'     => 'Application\\Service\\StatutIntervenant',
            'ApplicationDossier'               => 'Application\\Service\\Dossier',
            'ApplicationPieceJointe'           => 'Application\\Service\\PieceJointe',
            'ApplicationPieceJointeProcess'    => 'Application\\Service\\Process\PieceJointeProcess',
            'ApplicationTypePieceJointe'       => 'Application\\Service\\TypePieceJointe',
            'ApplicationTypePieceJointeStatut' => 'Application\\Service\\TypePieceJointeStatut',
            'ApplicationWorkflowIntervenantExterieur' => 'Application\\Service\\Workflow\\WorkflowIntervenantExterieur',
            'ApplicationWorkflowIntervenantPermanent' => 'Application\\Service\\Workflow\\WorkflowIntervenantPermanent',
        ),
        'initializers' => array(
            'Application\Service\Initializer\IntervenantServiceAwareInitializer',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'Workflow' => 'Application\View\Helper\Workflow',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
            'IntervenantDossier' => 'Application\Form\Intervenant\Dossier',
            'IntervenantModificationServiceDuForm'          => 'Application\Form\Intervenant\ModificationServiceDuForm',
            'IntervenantModificationServiceDuFieldset'      => 'Application\Form\Intervenant\ModificationServiceDuFieldset',
            'IntervenantMotifModificationServiceDuFieldset' => 'Application\Form\Intervenant\MotifModificationServiceDuFieldset',
        ),
    ),
);
