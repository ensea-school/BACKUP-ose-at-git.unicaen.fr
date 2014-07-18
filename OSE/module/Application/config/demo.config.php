<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'demo' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/demo[/:action]',
                    'constraints' => array(
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Demo',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
//                    'demo' => array(
//                        'label'    => 'Démo',
//                        'route'    => 'demo',
//                        'params' => array(
//                            'action' => 'index',
//                        ),
//                        'pages' => array(
////                            'of' => array(
////                                'label'  => "Offre de formation",
////                                'route'  => 'demo',
////                                'params' => array(
////                                    'action' => 'of',
////                                ),
////                                'visible' => true,
////                                'pages' => array(),
////                            ),
//                            'intervenant' => array(
//                                'label'  => "Intervenants",
//                                'route'  => 'demo',
//                                'params' => array(
//                                    'action' => 'intervenant',
//                                ),
//                                'visible' => true,
//                                'pages' => array(),
//                            ),
//                            'service-ref' => array(
//                                'label'  => "Service référentiel",
//                                'route'  => 'demo',
//                                'params' => array(
//                                    'action' => 'saisir-service-referentiel-intervenant',
//                                ),
//                                'visible' => true,
//                                'pages' => array(),
//                            ),
//                        ),
//                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Demo',
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Demo'        => 'Application\Controller\DemoController',
        ),
    ),
);
