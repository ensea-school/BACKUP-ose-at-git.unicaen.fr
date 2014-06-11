<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;

return array(
    'router' => array(
        'routes' => array(
            'of' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/offre-de-formation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'OffreFormation',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
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
                    'element' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/element',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                'controller'    => 'ElementPedagogique',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/:action[/:id]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]*',
                                    ),
                                ),
                            ),
                            'voir' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/voir/:id',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'voir' ),
                                ),
                            ),
                            'apercevoir' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/apercevoir/:id',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'apercevoir' ),
                                ),
                            ),
                            'ajouter' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/ajouter/:etape',
                                    'constraints' => array( 'etape' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'ajouter' ),
                                ),
                            ),
                            'modifier' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/modifier/:etape/:id',
                                    'constraints' => array(
                                        'etape' => '[0-9]*',
                                        'id'    => '[0-9]*',
                                    ),
                                    'defaults' => array( 'action' => 'modifier' ),
                                ),
                            ),
                            'supprimer' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/supprimer/:id',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'supprimer' ),
                                ),
                            ),
                            'get-periode' => array(
                                'type'  => 'Segment',
                                'options' => array(
                                    'route' => '/get-periode/:elementPedagogique',
                                    'constraints' => array( 'elementPedagogique' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'getPeriode' ),
                                ),
                            ),
                        ),
                    ),
                    'etape' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/etape',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                'controller'    => 'Etape',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/:action[/:id]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'     => '[0-9]*',
                                    ),
                                ),
                            ),
                            'voir' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/voir/:id',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'voir' ),
                                ),
                            ),
                            'apercevoir' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/apercevoir/:id',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'apercevoir' ),
                                ),
                            ),
                            'ajouter' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/ajouter/:structure',
                                    'constraints' => array( 'id' => '[0-9]*', ),
                                    'defaults' => array( 'action' => 'ajouter' ),
                                ),
                            ),
                            'modifier' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/modifier/:structure/:id',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'modifier' ),
                                ),
                            ),
                            'supprimer' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/supprimer/:id',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'supprimer' ),
                                ),
                            ),
                            'modulateurs' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/:etape/modulateurs',
                                    'constraints' => array(
                                        'etape' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        '__NAMESPACE__' => 'Application\Controller\OffreFormation',
                                        'controller'    => 'Modulateur',
                                        'action'        => 'saisir'
                                    ),
                                ),
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
                    'of' => array(
                        'label'    => 'Offre de formation',
                        'title'    => "Gestion de l'offre de formation",
                        'route'    => 'of',
                        'resource' => 'controller/Application\Controller\OffreFormation:index',
                        'pages' => array(
                            'element-ajouter' => array(
                                'label'    => "Créer un nouvel élément pédagogique",
                                'title'    => "Créer un nouvel élément pédagogique pour l'étape sélectionnée",
                                'route'    => 'of/element/ajouter',
                                'resource' => 'controller/Application\Controller\OffreFormation\ElementPedagogique:ajouter',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-plus',
                                'category' => 'element',
                            ),
                            'element-modifier' => array(
                                'label'    => "Modifier cet élément",
                                'title'    => "Modifier cet élément pédagogique",
                                'route'    => 'of/element/modifier',
                                'resource' => 'controller/Application\Controller\OffreFormation\ElementPedagogique:modifier',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-edit',
                                'withtarget' => true,
                                'category' => 'element',
                            ),
                            'element-supprimer' => array(
                                'label'    => "Supprimer cette étape",
                                'title'    => "Supprimer cette étape",
                                'route'    => 'of/element/supprimer',
                                'resource' => 'controller/Application\Controller\OffreFormation\ElementPedagogique:supprimer',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-remove',
                                'withtarget' => true,
                                'category' => 'element',
                            ),
                            'etape-ajouter' => array(
                                'label'    => "Créer une nouvelle étape",
                                'title'    => "Créer une nouvelle étape",
                                'route'    => 'of/etape/ajouter',
                                'resource' => 'controller/Application\Controller\OffreFormation\Etape:ajouter',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-plus',
                                'category' => 'etape',
                            ),
                            'etape-modifier' => array(
                                'label'    => "Modifier cette étape",
                                'title'    => "Modifier cette étape",
                                'route'    => 'of/etape/modifier',
                                'resource' => 'controller/Application\Controller\OffreFormation\Etape:modifier',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-edit',
                                'withtarget' => true,
                                'category' => 'etape',
                            ),
                            'etape-supprimer' => array(
                                'label'    => "Supprimer cette étape",
                                'title'    => "Supprimer cette étape",
                                'route'    => 'of/etape/supprimer',
                                'resource' => 'controller/Application\Controller\OffreFormation\Etape:supprimer',
                                'visible'  => false,
                                'icon'     => 'glyphicon glyphicon-remove',
                                'withtarget' => true,
                                'category' => 'etape',
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
                /**
                 * OffreFormation
                 */
                array(
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => array('search-structures', 'search-niveaux'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\OffreFormation',
                    'action'     => array('index'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
                /**
                 * Etape
                 */
                array(
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => array('voir', 'apercevoir', 'search'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\OffreFormation\Etape',
                    'action'     => array('ajouter', 'modifier', 'supprimer'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
                /**
                 * ElementPedagogique
                 */
                array(
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => array('voir', 'apercevoir', 'search', 'getPeriode'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\OffreFormation\ElementPedagogique',
                    'action'     => array('ajouter', 'modifier', 'supprimer'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
                /**
                 * Modulateur
                 */
                array(
                    'controller' => 'Application\Controller\OffreFormation\Modulateur',
                    'action'     => array('saisir'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\OffreFormation'                    => 'Application\Controller\OffreFormationController',
            'Application\Controller\OffreFormation\Etape'              => 'Application\Controller\OffreFormation\EtapeController',
            'Application\Controller\OffreFormation\Modulateur'         => 'Application\Controller\OffreFormation\ModulateurController',
            'Application\Controller\OffreFormation\ElementPedagogique' => 'Application\Controller\OffreFormation\ElementPedagogiqueController',
        ),
        'initializers' => array(
            'Application\Service\ContextProviderAwareInitializer',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationElementPedagogique'           => 'Application\\Service\\ElementPedagogique',
            'ApplicationCheminPedagogique'            => 'Application\\Service\\CheminPedagogique',
            'ApplicationEtape'                        => 'Application\\Service\\Etape',
            'ApplicationTypeFormation'                => 'Application\\Service\\TypeFormation',
            'FormElementPedagogiqueRechercheHydrator' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheHydrator',
            'OffreFormationAssertion'                 => 'Application\\Service\\OffreFormationAssertion',
            'ApplicationModulateur'                   => 'Application\\Service\\Modulateur',
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'FormElementPedagogiqueRechercheFieldset' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldsetFactory',
        ),
        'invokables' => array(
            'EtapeSaisie'              => 'Application\Form\OffreFormation\EtapeSaisie',
            'ElementPedagogiqueSaisie' => 'Application\Form\OffreFormation\ElementPedagogiqueSaisie',
        ),
        'initializers' => array(
            'Application\Service\ContextProviderAwareInitializer',
        ),
    ),

);