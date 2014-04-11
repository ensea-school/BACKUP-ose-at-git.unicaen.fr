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
                            'route'    => '/voirLigne[/:id][?only-content=:only-content]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voirLigne',
                                'only-content' => 0
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
                        'title'    => "Gestion des services",
                        'route'    => 'service',
                        'params' => array(
                            'action' => 'index',
                        ),
                        'pages' => array(
                            'consultation' => array(
                                'label'  => "Consultation",
                                'title'  => "Consultation des services",
                                'route'  => 'service',
                                'visible' => true,
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
                    'controller' => 'Application\Controller\Service',
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Service'   => 'Application\Controller\ServiceController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationService'            => 'Application\\Service\\Service',
            'ApplicationPeriode'            => 'Application\\Service\\Periode',
            'ApplicationMotifNonPaiement'   => 'Application\\Service\\MotifNonPaiement',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'serviceDl'         => 'Application\View\Helper\Service\Dl',
            'serviceListe'      => 'Application\View\Helper\Service\Liste',
            'serviceLigne'      => 'Application\View\Helper\Service\Ligne',
        ),
    ),
);
