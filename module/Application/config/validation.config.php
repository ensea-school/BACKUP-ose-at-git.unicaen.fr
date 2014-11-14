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
            'validation' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/validation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Validation',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'voir' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:validation',
                            'constraints' => array(
                                'validation' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir',
                                'validation' => 0,
                            ),
                        ),
                    ),
                    'supprimer' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:validation/supprimer',
                            'constraints' => array(
                                'validation' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'supprimer',
                            ),
                        ),
                    ),
                    'liste' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:typeValidation/:intervenant/liste',
                            'constraints' => array(
                                'typeValidation' => '[0-9]*',
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'liste',
                                'typeValidation' => 0,
                                'intervenant' => 0,
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
                    'validation' => array(
                        'label'    => 'Validation',
                        'route'    => 'validation/liste',
                        'visible'  => false,
                        'resource' => 'controller/Application\Controller\Validation:liste',
                        'pages' => array(
                            'voir' => array(
                                'label'  => "Détails",
                                'title'  => "Détails d'une validation",
                                'route'  => 'validation/voir',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Validation:voir',
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
                    'controller' => 'Application\Controller\Validation',
                    'action'     => array('index', 'liste', 'voir'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Validation',
                    'action'     => array('dossier'),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Validation',
                    'action'     => array('service', 'referentiel'),
                    'roles'      => array(IntervenantRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Validation',
                    'action'     => array('supprimer'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Validation' => 'Application\Controller\ValidationController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationTypeValidation'        => 'Application\\Service\\TypeValidation',
            'ApplicationValidation'            => 'Application\\Service\\Validation',
        ),
        'initializers' => array(
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
