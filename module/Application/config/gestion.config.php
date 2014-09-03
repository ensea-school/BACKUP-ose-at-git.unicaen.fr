<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'gestion' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route' => '/gestion',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'gestion',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    'gestion' => array(
                        'label'  => "Gestion",
                        'route'  => 'gestion',
                        'resource' => 'controller/Application\Controller\Index:gestion',
                    ),
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Index',
                    'action'     => array('gestion'),
                    'roles'      => array(\Application\Acl\ComposanteRole::ROLE_ID, 'Administrateur'),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
        ),
        'factories' => array(
        ),
        'abstract_factories' => array(
        ),
        'initializers' => array(
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
        'initializers' => array(
        ),
    ),
    'form_elements' => array(
        'initializers' => array(
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        ),
    ),
);