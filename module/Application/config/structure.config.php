<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'structure' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/structure',
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Structure',
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
                    'structure' => array(
                        'label'    => 'Structures',
                        'title'    => "Gestion des structures",
                        'route'    => 'structure',
                        'visible'  => false,
                        'params' => array(
                            'action' => 'index',
                        ),
                        'pages' => array(
                            'voir' => array(
                                'label'  => "Voir",
                                'title'  => "Voir une structure",
                                'route'  => 'structure',
                                'visible' => false,
                                'withtarget' => true,
                                'pages' => array(),
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
                    'controller' => 'Application\Controller\Structure',
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Structure'   => 'Application\Controller\StructureController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationStructure'       => 'Application\\Service\\Structure',
            'ApplicationTypeStructure'   => 'Application\\Service\\TypeStructure',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'structureDl'       => 'Application\View\Helper\StructureDl',
        ),
    ),
);
