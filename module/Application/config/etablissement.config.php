<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'etablissement' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/etablissement',
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'etablissement',
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
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Etablissement',
                    'action' => array('index', 'choisir', 'recherche', 'voir', 'apercevoir'),
                    'roles' => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID)),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Etablissement'   => 'Application\Controller\EtablissementController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationEtablissement'       => 'Application\\Service\\Etablissement',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'etablissementDl'   => 'Application\View\Helper\EtablissementDl',
        ),
    ),
);
