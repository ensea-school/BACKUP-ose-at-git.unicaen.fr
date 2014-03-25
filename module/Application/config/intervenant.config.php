<?php

namespace Application;

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
                    'intervenant' => array(
                        'label'    => 'Intervenant',
                        'title'    => "Gestion des intervenants",
                        'route'    => 'intervenant',
                        'resource' => 'controller/Application\Controller\Intervenant:index',
                        'pages' => array(
//                            'rechercher' => array(
//                                'label'  => "Rechercher",
//                                'title'  => "Rechercher un intervenant",
//                                'route'  => 'intervenant/default',
//                                'params' => array(
//                                    'action' => 'rechercher',
//                                ),
//                                'visible' => true,
//                                'pages' => array(),
//                            ),
                            'voir' => array(
                                'label'  => "Voir",
                                'title'  => "Voir l'intervenant {id}",
                                'route'  => 'intervenant/default',
                                'visible' => false,
                                'withtarget' => true,
                                'pages' => array(),
                            ),
//                            'modifier' => array(
//                                'label'  => "Modifier",
//                                'title'  => "Modifier l'intervenant {id}",
//                                'route'  => 'intervenant/modifier',
//                                'visible' => false,
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
                    'controller' => 'Application\Controller\Intervenant',
                    'action' => array('index', 'choisir', 'modifier', 'rechercher', 'voir', 'saisirServiceReferentiel', 'search'),
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Intervenant' => 'Application\Controller\IntervenantController',
        ),
    ),
);