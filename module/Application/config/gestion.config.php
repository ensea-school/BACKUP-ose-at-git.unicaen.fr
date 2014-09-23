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
                    'roles'      => array(ComposanteRole::ROLE_ID, AdministrateurRole::ROLE_ID),
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