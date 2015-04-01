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

return [
    'router' => [
        'routes' => [
            'workflow' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/workflow',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Workflow',
                    ],
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'nav-next' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route' => '/:intervenant',
                            'constraints' => [
                                'intervenant' => '[0-9]*',
                            ],
                            'defaults' => [
                                'action' => 'nav-next',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [

                ],
            ],
        ],
    ],
    'bjyauthorize' => [
        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                [
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => ['nav-next'],
                    'roles'      => ['user'],
                ],
            ],
        ],
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
    ],
    'controllers' => [
        'invokables' => [
            'Application\Controller\Workflow' => 'Application\Controller\WorkflowController',
        ],
        'initializers' => [
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'WfEtapeService'            => 'Application\\Service\\WfEtape',
            'WfIntervenantEtapeService' => 'Application\\Service\\WfIntervenantEtape',
            'WorkflowIntervenant'       => 'Application\\Service\\Workflow\\WorkflowIntervenant',
            'DbFunctionRule'            => 'Application\Rule\Intervenant\DbFunctionRule',
        ],
        'factories' => [
        ],
        'initializers' => [
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'Workflow' => 'Application\View\Helper\Workflow',
        ],
        'initializers' => [
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ],
    ],
    'form_elements' => [
        'invokables' => [
        ],
    ],
];
