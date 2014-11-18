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
            'workflow' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route' => '/workflow/:intervenant/:action',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'intervenant' => '[0-9]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Workflow',
                    ),
                ),
            ),
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
                    'controller' => 'Application\Controller\Workflow',
                    'action'     => array('nav-next', 'intervenants'),
                    'roles'      => array('user'),
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
            'Application\Controller\Workflow' => 'Application\Controller\WorkflowController',
        ),
        'initializers' => array(
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'WorkflowIntervenant'  => 'Application\\Service\\Workflow\\WorkflowIntervenant',
            'WorkflowQueryBuilder' => 'Application\\Service\\Workflow\\WorkflowQueryBuilder',
        ),
        'initializers' => array(
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'Workflow' => 'Application\View\Helper\Workflow',
        ),
        'initializers' => array(
            'Application\Service\Workflow\WorkflowIntervenantAwareInitializer',
        ),
    ),
    'form_elements' => array(
        'invokables' => array(
        ),
    ),
);
