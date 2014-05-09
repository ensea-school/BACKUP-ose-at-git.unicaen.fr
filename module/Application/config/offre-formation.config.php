<?php

namespace Application;

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
                    'etape' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/etape',
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'apercevoir' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/apercevoir[/:id]',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'etapeApercevoir' ),
                                ),
                            ),
                            'saisie' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/saisie[/:id]',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'etapeSaisie' ),
                                ),
                            ),
                            'suppression' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/suppression[/:id]',
                                    'constraints' => array( 'id' => '[0-9]*' ),
                                    'defaults' => array( 'action' => 'etapeSuppression' ),
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
//                        'resource' => 'controller/Application\Controller\OffreFormation:index',
                        'pages' => array(

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
                    'controller' => 'Application\Controller\OffreFormation',
//                    'action' => array(),
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\OffreFormation' => 'Application\Controller\OffreFormationController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationElementPedagogique'           => 'Application\\Service\\ElementPedagogique',
            'ApplicationEtape'                        => 'Application\\Service\\Etape',
            'ApplicationTypeFormation'                => 'Application\\Service\\TypeFormation',
            'FormElementPedagogiqueRechercheHydrator' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheHydrator'
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'FormElementPedagogiqueRechercheFieldset' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldsetFactory',
        ),
        'invokables' => array(
            'EtapeSaisie' => 'Application\Form\OffreFormation\EtapeSaisie',
        ),
    ),

);