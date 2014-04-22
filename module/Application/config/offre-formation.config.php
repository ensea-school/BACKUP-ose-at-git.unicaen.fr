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
            'FormElementPedagogiqueRechercheHydrator' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheHydrator'
        ),
    ),
    'form_elements' => array(
        'factories' => array(
            'FormElementPedagogiqueRechercheFieldset' => 'Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldsetFactory',
        ),
    ),
);