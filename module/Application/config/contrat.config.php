<?php

namespace Application;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantExterieurRole;
    
return array(
    'router' => array(
        'routes' => array(
            'contrat' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/contrat',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Contrat',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'creer' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'creer-contrat',
                            ),
                        ),
                    ),
                    'creer-avenant' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'creer-avenant',
                            ),
                        ),
                    ),
                    'voir' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:intervenant',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'voir',
                            ),
                        ),
                    ),
                    'exporter' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route' => '/:intervenant/exporter',
                            'constraints' => array(
                                'intervenant' => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'exporter',
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
                    'contrat' => array(
                        'label'    => 'Contrat et avenants',
                        'title'    => "Contrat et avenants de l'intervenant",
                        'route'    => 'contrat/voir',
                        'withtarget' => true,
                        'resource' => 'controller/Application\Controller\Contrat:voir',
                        'pages' => array(
                            'exporter-contrat' => array(
                                'label'  => "Exporter",
                                'title'  => "Exporter le contrat de l'intervenant au format PDF",
                                'route'  => 'contrat/exporter',
                                'withtarget' => true,
                                'resource' => 'controller/Application\Controller\Contrat:exporter',
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
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => array('creer', 'creer-avenant'),
                    'roles'      => array(ComposanteRole::ROLE_ID),
                ),
                array(
                    'controller' => 'Application\Controller\Contrat',
                    'action'     => array('index', 'voir', 'exporter'),
                    'roles'      => array(IntervenantExterieurRole::ROLE_ID, ComposanteRole::ROLE_ID),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Contrat' => 'Application\Controller\ContratController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'ApplicationContrat'     => 'Application\\Service\\Contrat',
            'ApplicationTypeContrat' => 'Application\\Service\\TypeContrat',
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
