<?php

namespace Application;

use Application\Acl\Role;
use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DirecteurComposanteRole;
use Application\Acl\GestionnaireComposanteRole;
use Application\Acl\ResponsableComposanteRole;
use Application\Acl\SuperviseurComposanteRole;
use Application\Acl\ResponsableRechercheLaboRole;
use Application\Acl\DrhRole;
use Application\Acl\GestionnaireDrhRole;
use Application\Acl\ResponsableDrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\SuperviseurEtablissementRole;
use Application\Acl\IntervenantRole;
use Application\Acl\IntervenantPermanentRole;
use Application\Acl\IntervenantExterieurRole;
use Application\Acl\FoadRole;
use Application\Acl\ResponsableFoadRole;

return array(
    'router' => array(
        'routes' => array(
            'notification' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/notification',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Notification',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'indicateurs' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/indicateurs',
                            'defaults' => array(
                                'action' => 'indicateurs',
                            ),
                        ),
                    ),
                    'indicateur-fetch-title' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/indicateur-fetch-title',
                            'defaults' => array(
                                'action' => 'indicateur-fetch-title',
                            ),
                        ),
                    ),
                    'notifier-indicateurs' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/notifier-indicateurs',
                            'defaults' => array(
                                'action' => 'notifier-indicateurs',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'notifier-indicateurs' => array(
                    'type'    => 'Simple',
                    'options' => array(
                        'route'    => 'notifier indicateurs [--force] --requestUriHost= [--requestUriScheme=]',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Notification',
                            'action'     => 'notifier-indicateurs'
                        )
                    )
                )
            )
        )
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
                    'controller' => 'Application\Controller\Notification',
                    'action'     => array('indicateurs', 'indicateur-fetch-title'),
                    'roles'      => array(AdministrateurRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Notification',
                    'action'     => array('notifier-indicateurs'),
                    'roles'      => array(),
                ),
            ),
        ),
//        'resource_providers' => array(
//            'BjyAuthorize\Provider\Resource\Config' => array(
//                'Intervenant' => [],
//            ),
//        ),
//        'rule_providers' => array(
//            'BjyAuthorize\Provider\Rule\Config' => array(
//                'allow' => array(
//                    array(
//                        $R_ALL,
//                        'Intervenant',
//                        array('total-heures-comp'),
//                        'IntervenantAssertion',
//                    ),
//                ),
//            ),
//        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Notification' => 'Application\Controller\NotificationController',
        ),
        'initializers' => array(
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
        ),
        'factories' => array(
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
        'invokables' => array(
        ),
    ),
);