<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'recherche' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/recherche/:action',
                    'constraints' => array(
                        'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'typeIntervenant'   => '[0-9]*',
                        'structure'         => '[0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Recherche',
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Recherche',
                    'roles' => array('user')),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Recherche'   => 'Application\Controller\RechercheController',
        ),
    ),
);