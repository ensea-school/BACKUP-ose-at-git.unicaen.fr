<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'volume_horaire' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/volume_horaire',
                    'defaults' => array(
                       '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'VolumeHoraire',
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
                    'volume_horaire' => array(
                        'label'    => 'Volumes horaires',
                        'title'    => "Gestion des volumes horaires",
                        'visible' => false,
                        'route'    => 'volume_horaire',
                        'params' => array(
                            'action' => 'index',
                        ),
                        'pages' => array(
                            'consultation' => array(
                                'label'  => "Consultation",
                                'title'  => "Consultation des volumes horaires",
                                'route'  => 'volume_horaire',
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
                    'controller' => 'Application\Controller\VolumeHoraire',
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\VolumeHoraire'   => 'Application\Controller\VolumeHoraireController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationVolumeHoraire' => 'Application\\Service\\VolumeHoraire',
        )
    ),
    'view_helpers' => array(
        'invokables' => array(
            'volumeHoraireDl'   => 'Application\View\Helper\VolumeHoraire\Dl',
            'volumeHoraireListe'   => 'Application\View\Helper\VolumeHoraire\Liste',
            'volumeHoraireLigne'   => 'Application\View\Helper\VolumeHoraire\Ligne',
        ),
    ),
);
