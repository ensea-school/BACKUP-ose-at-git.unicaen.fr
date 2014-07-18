<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;

return array(
    'router' => array(
        'routes' => array(
//            'agrement' => array(
//                'type'    => 'Literal',
//                'options' => array(
//                    'route'    => '/agrement',
//                    'defaults' => array(
//                        '__NAMESPACE__' => 'Application\Controller',
//                        'controller'    => 'Intervenant',
//                        'action'        => 'index',
//                    ),
//                ),
//                'may_terminate' => true,
//                'child_routes' => array(
//                    'intervenant' => array(
//                        'type'    => 'Segment',
//                        'options' => array(
//                            'route' => '/agrement/:typeAgrement/:intervenant',
//                            'constraints' => array(
//                                'intervenant' => '[0-9]*',
//                                'typeAgrement' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                            ),
//                            'defaults' => array(
//                                'controller' => 'Agrement',
//                                'action' => 'intervenant',
//                            ),
//                        ),
//                    ),
//                ),
//            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'home' => array(
                'pages' => array(
                    
                ),
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Agrement',
                    'action'     => array('voir', 'modifier'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID, 'Administrateur'),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Agrement' => 'Application\Controller\AgrementController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationAgrement'           => 'Application\\Service\\Agrement',
            'ApplicationTypeAgrement'       => 'Application\\Service\\TypeAgrement',
            'ApplicationTypeAgrementStatut' => 'Application\\Service\\TypeAgrementStatut',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
        ),
    ),
);
